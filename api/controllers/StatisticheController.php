<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Professore.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Utente.php";

class StatisticheController extends BaseController {

	public function registerRoutes($router) {
		$router->add('GET', '/statistiche/ai', [$this, 'getAIAnalysis']);
		$router->add('GET', '/statistiche/correzioni', [$this, 'getCorrezioni']);
	}

	public function getCorrezioni($params = []) {
		global $mysql;
		$userId = $this->requireAuth();
		$professore = Professore::getProfessoreByUtenteID($userId);
		
		$sql = "
			SELECT c.ID, c.data_verifica, v.titolo, c.note, c.analisi_ai
			FROM correzione c
			JOIN verifica v ON c.ID_verifica = v.ID
			WHERE v.ID_professore = '{$professore->id}'
			ORDER BY c.data_verifica DESC
		";
		$result = $mysql->query($sql);
		$correzioni = [];
		while($row = mysqli_fetch_assoc($result)) {
			$correzioni[] = [
				'id' => $row['ID'],
				'data' => date("d/m/Y", strtotime($row['data_verifica'])),
				'titolo' => $row['titolo'],
				'note' => $row['note'],
				'analisi_ai' => $row['analisi_ai']
			];
		}
		
		$this->json(['success' => true, 'data' => $correzioni]);
	}

	public function getAIAnalysis($params = []) {
		global $mysql;
		
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/config.php";
		global $api_key_gemini;
		
		$userId = $this->requireAuth();
		$professore = Professore::getProfessoreByUtenteID($userId);
		if (!$professore) {
			$this->error('Professore non trovato', 404);
		}
		
		$correzioniFilter = "";
		if (isset($_GET['correzioni']) && !empty($_GET['correzioni'])) {
			$ids = array_map('intval', explode(',', $_GET['correzioni']));
			if (count($ids) > 0) {
				$idsStr = implode(',', $ids);
				$correzioniFilter = "AND c.ID IN ($idsStr)";
			}
		}
		
		// Raccogliamo le domande valutate con punteggio inferiore al massimo
		$sql = "
			SELECT cd.ID 
			FROM correzione_domanda cd
			JOIN correzione c ON cd.ID_correzione = c.ID
			JOIN verifica v ON c.ID_verifica = v.ID
			WHERE v.ID_professore = '{$professore->id}' $correzioniFilter
			ORDER BY c.data_verifica DESC
			LIMIT 150
		";
		
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/CorrezioneDomanda.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Correzione.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verifica.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verofalso.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/RispostaAperta.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/RispostaMultipla.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Esercizio.php";

		$result = $mysql->query($sql);
		$errori = [];
		$maxErrors = 30; // Limite errori da mandare a Gemini
		
		while($row = mysqli_fetch_assoc($result)) {
			if (count($errori) >= $maxErrors) break;
			
			$cd = new CorrezioneDomanda($row['ID']);
			$tipo = $cd->tipologia_esercizio;
			
			if (class_exists($tipo)) {
				$esercizio = new $tipo($cd->ID_esercizio);
				$punteggioMax = $esercizio->punteggio;
				$punteggioOttenuto = $cd->getPunteggio();
				
				if ($punteggioOttenuto < $punteggioMax) {
					$testo = isset($esercizio->testo) ? strip_tags(str_replace("<br>", " ", $esercizio->testo)) : "";
					$titolo = isset($esercizio->titolo) ? strip_tags(str_replace("<br>", " ", $esercizio->titolo)) : $tipo;
					
					$errori[] = [
						'esercizio_titolo' => $titolo,
						'esercizio_testo' => $testo,
						'punteggio_ottenuto' => $punteggioOttenuto,
						'max_punteggio' => $punteggioMax
					];
				}
			}
		}
		
		if (count($errori) === 0) {
			$this->json(['success' => true, 'data' => "Non ci sono errori registrati per la selezione attuale."]);
		}
		
		// Prepariamo il prompt per Gemini
		$prompt = "Sei un assistente per docenti molto esperto. Di seguito ti fornisco una lista di errori comuni commessi dagli studenti nelle ultime verifiche (massimo 50 errori recenti). ";
		$prompt .= "Ogni voce contiene il titolo dell'esercizio, il testo dell'esercizio, e l'errore fatto (o il fatto che la risposta è stata sbagliata). ";
		$prompt .= "Per favore, analizza questi dati e fornisci un riassunto dei concetti o degli argomenti che sembrano essere più difficili per gli studenti. ";
		$prompt .= "Struttura la tua risposta in markdown con: \n";
		$prompt .= "1. Una breve panoramica generale.\n";
		$prompt .= "2. I 3-5 argomenti/concetti più problematici.\n";
		$prompt .= "3. Suggerimenti pratici su come rinforzare questi concetti in classe.\n";
		$prompt .= "4. Tabella riepilogativa degli errori per ogni esercizio.\n\n";
		$prompt .= "Ecco i dati degli errori:\n";
		
		foreach ($errori as $i => $err) {
			$prompt .= "- Esercizio: " . strip_tags(str_replace("<br>", " ", $err['esercizio_titolo'])) . "\n";
			$prompt .= "  Testo: " . strip_tags(str_replace("<br>", " ", $err['esercizio_testo'])) . "\n";
			$prompt .= "  (Ha ottenuto un punteggio parziale/nullo)\n";
		}
		
		// Chiamata all'API di Gemini 3.1 Flash Lite
		if(empty($api_key_gemini)) {
			$this->error("Chiave API di Gemini non configurata. Impostare \$api_key_gemini in inc/config.php o come variabile d'ambiente.", 500);
		}
		
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key=' . $api_key_gemini;
		
		$data = [
			"contents" => [
				[
					"parts" => [
						["text" => $prompt]
					]
				]
			]
		];
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($httpCode >= 400) {
			$this->error("Errore nell'analisi AI: " . $response, 500);
		}
		
		$responseData = json_decode($response, true);
		$text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "Impossibile generare l'analisi in questo momento.";
		
		// Salvataggio nel DB se si sta analizzando una singola correzione
		if (isset($_GET['correzioni'])) {
			$ids = array_map('intval', explode(',', $_GET['correzioni']));
			if (count($ids) == 1) {
				$id_corr = $ids[0];
				$mysql->update("correzione", "ID = '$id_corr'", ["analisi_ai" => $text]);
			}
		}
		
		$this->json([
			'success' => true,
			'data' => $text
		]);
	}
}
