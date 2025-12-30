<?php
	global $message;
	
	function salvaVerifica(): void {
		global $message;
		
		if(verifyAllPostVars(["titolo", "id", "materia", "classe"])) {
			if(Verifica::edit($_POST["id"], [
				"titolo" => $_POST["titolo"],
				"ID_materia" => $_POST["materia"],
				"ID_classe" => $_POST["classe"],
				"note" => $_POST["note"] ?? "",
			])){
				$message->setMessageType(MessageType::Success)
					->setMessage("Verifica modificata correttamente")
					->show();
			}else{
				$message->setMessageType(MessageType::Error)
					->setMessage("Errore nel salvataggio della verifica")
					->show();
			}
		}
	}
	
	function modificaSezione(): void {
		global $message;
		// Se l'id è uguale a 0 aggiungo una nuova sezione
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["titolo", "ID_verifica"])) {
				$ordine = Sezione::getUltimoOrdineVerifica($_POST["ID_verifica"]) + 1;
				if(Sezione::create(["titolo" => $_POST["titolo"], "ordine" => $ordine, "ID_verifica" => $_POST["ID_verifica"]])){
					$message->setMessageType(MessageType::Success)
						->setMessage("Sezione creata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della sezione")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["titolo"])) {
				if(Sezione::edit($_POST["id"], ["titolo" => $_POST["titolo"]])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Sezione modificata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della sezione")
						->show();
				}
			}
		}
	}
	
	function eliminaSezione(): void {
		global $message;
		
		if(Sezione::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Sezione eliminata correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione della sezione")
				->show();
		}
	}
	
	function modificaVerofalso(): void {
		global $message;
		
		// Se l'id è uguale a 0 aggiungo un nuovo verofalso
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["testo", "ID_sezione", "risultato", "punteggio"])) {
				$ID_sezione = $_POST["ID_sezione"];
				$sezione = new Sezione($ID_sezione, ["ID_verifica"]);
				$ordine = Sezione::getUltimoOrdineSezione($ID_sezione) + 1;
				$ID_verifica = $sezione->ID_verifica;
				
				if(Verofalso::create([
					"testo" => $_POST["testo"],
					"ID_sezione" => $ID_sezione,
					"ID_verifica" => $ID_verifica,
					"risultato" => ($_POST["risultato"] == "1" ? "1" : "0"),
					"punteggio" => $_POST["punteggio"],
					"note" => $_POST["note"] ?? "",
					"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Vero-Falso creato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio del Vero-Falso")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["testo", "risultato", "punteggio"])) {
				if(Verofalso::edit(
					$_POST["id"],
					[
						"testo" => $_POST["testo"],
						"risultato" => $_POST["risultato"],
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? ""
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Vero-Falso modificato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio del Vero-Falso")
						->show();
				}
			}
		}
	}
	
	function eliminaVerofalso(): void {
		global $message;
		
		if(Verofalso::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Vero-Falso eliminato correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione del Vero-Falso")
				->show();
		}
	}
	
	function modificaRispostaaperta(): void {
		global $message;
		
		// Se l'id è uguale a 0 aggiungo una nuova risposta aperta
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["testo", "ID_sezione", "punteggio"])) {
				$ID_sezione = $_POST["ID_sezione"];
				$sezione = new Sezione($ID_sezione, ["ID_verifica"]);
				$ordine = Sezione::getUltimoOrdineSezione($ID_sezione) + 1;
				$ID_verifica = $sezione->ID_verifica;
				
				if(Rispostaaperta::create([
						"testo" => $_POST["testo"],
						"ID_sezione" => $ID_sezione,
						"ID_verifica" => $ID_verifica,
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? "",
						"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Domanda aperta creata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Domanda aperta")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["testo", "punteggio"])) {
				if(Rispostaaperta::edit(
					$_POST["id"],
					[
						"testo" => $_POST["testo"],
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? ""
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Domanda aperta modificata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Domanda aperta")
						->show();
				}
			}
		}
	}
	
	function eliminaRispostaaperta(): void {
		global $message;
		
		if(Rispostaaperta::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Domanda aperta eliminata correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione della Domanda aperta")
				->show();
		}
	}
	
	function modificaRispostamultipla(): void {
		global $message;
		
		// Se l'id è uguale a 0 aggiungo una nuova risposta aperta
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["testo", "ID_sezione", "punteggio"])) {
				$ID_sezione = $_POST["ID_sezione"];
				$sezione = new Sezione($ID_sezione, ["ID_verifica"]);
				$ordine = Sezione::getUltimoOrdineSezione($ID_sezione) + 1;
				$ID_verifica = $sezione->ID_verifica;
				
				if(Rispostamultipla::create([
						"testo" => $_POST["testo"],
						"ID_sezione" => $ID_sezione,
						"ID_verifica" => $ID_verifica,
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? "",
						"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Domanda chiusa creata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Domanda chiusa")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["testo", "punteggio"])) {
				if(Rispostamultipla::edit(
					$_POST["id"],
					[
						"testo" => $_POST["testo"],
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? ""
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Domanda chiusa modificata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Domanda chiusa")
						->show();
				}
			}
		}
	}
	
	function eliminaRispostamultipla(): void {
		global $message;
		
		if(Rispostamultipla::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Domanda chiusa eliminata correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione della Domanda chiusa")
				->show();
		}
	}
	
	function modificaRisposta(): void {
		global $message;
		
		// Se l'id è uguale a 0 aggiungo una nuova risposta aperta
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["testo", "ID_rispostamultipla", "punteggio"])) {
				$ID_rispostamultipla = $_POST["ID_rispostamultipla"];
				$ordine = Rispostamultipla::getUltimoOrdineRisposta($ID_rispostamultipla) + 1;
				
				if(Rispostamultipla::createRisposta([
						"ID_rispostamultipla" => $ID_rispostamultipla,
						"testo" => $_POST["testo"],
						"punteggio" => $_POST["punteggio"],
						"corretto" => ($_POST["corretto"] == "1" ? "1" : "0"),
						"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Risposta creata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Risposta")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["testo", "punteggio"])) {
				if(Rispostamultipla::editRisposta(
					$_POST["id"],
					[
						"testo" => $_POST["testo"],
						"punteggio" => $_POST["punteggio"],
						"corretto" => ($_POST["corretto"] == "1" ? "1" : "0"),
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Risposta modificata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della Risposta")
						->show();
				}
			}
		}
	}
	
	function eliminaRisposta(): void {
		global $message;
		
		if(Rispostamultipla::deleteRisposta($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Risposta eliminata correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione della Risposta")
				->show();
		}
	}
	
	function modificaEsercizio(): void {
		global $message;
		
		// Se l'id è uguale a 0 aggiungo una nuova risposta aperta
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["titolo", "testo", "ID_sezione", "punteggio"])) {
				$ID_sezione = $_POST["ID_sezione"];
				$sezione = new Sezione($ID_sezione, ["ID_verifica"]);
				$ID_verifica = $sezione->ID_verifica;
				$ordine = Sezione::getUltimoOrdineSezione($ID_sezione) + 1;
				
				if(Esercizio::create([
					"ID_sezione" => $ID_sezione,
					"ID_verifica" => $ID_verifica,
					"titolo" => $_POST["titolo"],
					"testo" => $_POST["testo"],
					"punteggio" => $_POST["punteggio"],
					"note" => $_POST["note"] ?? "",
					"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Esercizio creato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio dell'Esercizio")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["titolo", "testo", "punteggio"])) {
				if(Esercizio::edit(
					$_POST["id"],
					[
						"titolo" => $_POST["titolo"],
						"testo" => $_POST["testo"],
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? "",
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Esercizio modificato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio dell'Esercizio")
						->show();
				}
			}
		}
	}
	
	function eliminaEsercizio(): void {
		global $message;
		
		if(Esercizio::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Esercizio eliminato correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione dell'Esercizio")
				->show();
		}
	}
	
	
	$typeToFunction = array(
		"salva-verifica" => "salvaVerifica",
		"modifica-sezione" => "modificaSezione",
		"elimina-sezione" => "eliminaSezione",
		"modifica-verofalso" => "modificaVerofalso",
		"elimina-verofalso" => "eliminaVerofalso",
		"modifica-rispostaaperta" => "modificaRispostaaperta",
		"elimina-rispostaaperta" => "eliminaRispostaaperta",
		"modifica-rispostamultipla" => "modificaRispostamultipla",
		"elimina-rispostamultipla" => "eliminaRispostamultipla",
		"modifica-risposta" => "modificaRisposta",
		"elimina-risposta" => "eliminaRisposta",
		"modifica-esercizio" => "modificaEsercizio",
		"elimina-esercizio" => "eliminaEsercizio",
	);
	
	if(verifyAllPostVars(["id", "type"])){
		if(isset($typeToFunction[$_POST["type"]])) {
			call_user_func($typeToFunction[$_POST["type"]]);
		}
	}