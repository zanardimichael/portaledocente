<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	
	class RispostaMultipla extends Base {
		
		static $sqlNames = [
			"id",
			"ID_verifica",
			"ID_sezione",
			"testo",
			"note",
			"punteggio",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica_rispostamultipla";
		static $sqlTableRisposte = "verifica_rispostamultipla_risposte";
		
		public int $id;
		public ?int $ID_verifica;
		public Verifica $verifica {
			get {
				return new Verifica($this->ID_verifica);
			}
		}
		public ?int $ID_sezione;
		public Sezione $sezione {
			get {
				return new Sezione($this->ID_verifica);
			}
		}
		public string $testo;
		public ?string $note;
		public int $punteggio;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return RispostaMultipla[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new RispostaMultipla($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		static function create($data): bool{
			global $mysql;
			return $mysql->insert(static::$sqlTable, $data);
		}
		
		static function createRisposta($data): bool{
			global $mysql;
			return $mysql->insert(static::$sqlTableRisposte, $data);
		}
		
		static function edit($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTable, "ID='$id'", $data);
		}
		
		static function editRisposta($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTableRisposte, "ID='$id'", $data);
		}
		
		static function delete($id): bool{
			global $mysql;
			$rispostamultipla = new RispostaMultipla($id, ["ordine", "ID_sezione"]);
			$delete = $mysql->delete(static::$sqlTable, "ID='$id'");
			Sezione::updateOrdineEsercizi($rispostamultipla->ordine, $rispostamultipla->ID_sezione);
			return $delete;
		}
		
		static function deleteRisposta($id): bool{
			global $mysql;
			return $mysql->delete(static::$sqlTableRisposte, "ID='$id'");
		}
		
		static function existsRisposta($id): bool{
			global $mysql;
			return $mysql->select(static::$sqlTableRisposte, "id='$id'", "COUNT(ID) as ids")->fetch_assoc()["ids"] == 1;
		}
		
		public function getTimestampCreazioneTime() : int {
			return strtotime($this->timestamp_creazione);
		}
		
		public function getTimestampModificaTime() : int {
			return strtotime($this->timestamp_modifica);
		}
		
		public function render($ordine, $ordine_max): string {
			$disabled_su = $ordine == 1 ? "disabled" : "";
			$disabled_giu = $ordine == $ordine_max ? "disabled" : "";
			$risposte = $this->getRisposte();
			$risposte_txt = "";
			
			if(count($risposte) > 0) {
				foreach ($risposte as $risposta) {
					$risposte_txt .= '
					<div class="col-12" id-risposta="' . $risposta["ID"] . '">
						<div class="card">
							<div class="card-body p-2">
								<span class="mt-1 d-inline-block">' . $risposta["testo"] . ($risposta["corretto"] ? " <b>Corretta</b>" : "") . '</span>
								<div class="float-end">
									<div class="btn-group btn-group-sm">
										<button type="button" class="btn btn-sm btn-primary modifica-risposta" id-risposta="' . $risposta["ID"] . '">Modifica</button>
										<button type="button" class="btn btn-sm btn-danger elimina-risposta" id-risposta="' . $risposta["ID"] . '">Elimina</button>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}else{
				$risposte_txt = "Nessuna risposta inserita";
			}
			
			return '
				<div class="col-12 esercizio" id="esercizio-'.$this->id.'" id-rispostamultipla="'.$this->id.'" ordine="'.$this->ordine.'">
					<div class="card card-success card-outline">
						<div class="card-header">
							<div class="card-title">
								Risposta multipla
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary modifica-rispostamultipla" id-rispostamultipla="'.$this->id.'">Modifica</button>
									<button type="button" class="btn btn-sm btn-danger elimina-rispostamultipla" id-rispostamultipla="'.$this->id.'">Elimina</button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-giu-esercizio" '.$disabled_giu.' id-rispostamultipla="'.$this->id.'"><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-su-esercizio" '.$disabled_su.' id-rispostamultipla="'.$this->id.'"><i class="bi bi-chevron-up"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<h5>'.$this->testo.'</h5>
							<hr>
							<h5 class="mb-3 mt-2">
								Risposte
								<div class="float-end">
									<button type="button" class="btn btn-sm btn-primary aggiungi-risposta" id-rispostamultipla="'.$this->id.'">Aggiungi Risposta</button>
								</div>
							</h5>
							'.$risposte_txt.'
							<hr>
							<div>Punteggio: '.$this->punteggio.'</div>
						</div>
					</div>
				</div>';
		}
		
		public function renderCorrezione(CorrezioneDomanda $correzioneDomanda): string {
			$valori = [];
			if($correzioneDomanda->valore != ""){
				$valori = explode(",", $correzioneDomanda->valore);
			}
			$risposte_html = "";
			$risposte = $this->getRisposte();
			$risposte_corrette = [];
			$punteggio = 0;
			foreach ($risposte as $risposta) {
				$selected = in_array($risposta["ID"], $valori) ? "checked" : "";
				$risposte_html .= '
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="'.$risposta["ID"].'" name="risposta_multipla[]" id="risposta-multipla-'.$this->id.'-'.$risposta["ID"].'" '.$selected.'>
						<label class="form-check-label" for="risposta-multipla-'.$this->id.'-'.$risposta["ID"].'">
							'.$risposta["testo"].'
						</label>
					</div>';
			}
			
			$punteggio = $this->getPunteggioCorrezione($correzioneDomanda);
			$checked_parziale = $correzioneDomanda->parziale ? "checked" : ""; // Se parziale è selezionato la checkbox deve essere selezionata
			$punteggio_parziale = $correzioneDomanda->parziale ? $correzioneDomanda->punteggio : ""; // Se parziale è selezionato il punteggio deve essere inserito nell'input
			$input_punteggio_parziale = $correzioneDomanda->parziale ? "" : "disabled"; // Se parziale è selezionato l'input deve essere attivo altrimenti deve essere disabilitato
			
			$card_info = "card-warning";
			if($punteggio == 0){
				$card_info = "card-danger";
			}else if($punteggio == $this->punteggio){
				$card_info = "card-success";
			}else if($correzioneDomanda->valore == ""){
				$card_info = "card-info";
			}
			
			return '<form action="/api/rispostamultipla/correzione/'.$correzioneDomanda->id.'" id="'.$correzioneDomanda->id.'">
				<div class="card '.$card_info.' card-outline mb-2" id="correzione-'.$correzioneDomanda->id.'">
					<div class="card-header">
						Risposta Multipla
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
								<p>'.$this->testo.'</p>
								<label class="form-label">Risposte</label>
								'.$risposte_html.'
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label class="form-label">Parziale</label>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="1" name="parziale" id="parziale-risposta-multipla-'.$this->id.'" '.$checked_parziale.'>
									<label class="form-check-label" for="parziale-risposta-multipla-'.$this->id.'">
										Specifica punteggio parziale
									</label>
								</div>
								<hr>
								<label class="form-label" for="punteggio-parziale-risposta-multipla-'.$this->id.'">Punteggio Parziale</label>
								<input
										type="number"
										class="form-control"
										id="punteggio-parziale-risposta-multipla-'.$this->id.'"
										name="punteggio"
										value="'.$punteggio_parziale.'"
										step="0.1"
										min="0"
										max="64"
										required
										'.$input_punteggio_parziale.'
								/>
							</div>
						</div>
					</div>
					<div class="card-footer">
						Punteggio: <span class="risulato-risposta-multipla-'.$this->id.'">'.$punteggio.'</span>
					</div>
				</div></form>';
		}
		
		public function renderLatex(): string {
			$testo = "$this->testo
			\begin{todolist}\n";
			$risposte = $this->getRisposte();
			
			foreach($risposte as $risposta){
				$testo .= "\t\item ".$risposta["testo"]."\n";
			}
			
			$testo .= "\\end{todolist}\n";
			return $testo;
		}
		
		public function getRisposte(): array{
			global $mysql;
			
			$risposte_array = [];
			$risposte = $mysql->select("verifica_rispostamultipla_risposte", "ID_rispostamultipla='$this->id'");
			while($row = mysqli_fetch_assoc($risposte)){
				$risposte_array[] = $row;
			}
			return $risposte_array;
		}
		
		public function getRisposteCorrette($object = true): array{
			global $mysql;
			
			$risposte_array = [];
			$risposte = $mysql->select("verifica_rispostamultipla_risposte", "ID_rispostamultipla='$this->id' AND corretto=1");
			while($row = mysqli_fetch_assoc($risposte)){
				$risposte_array[] = $row["ID"];
			}
			return $risposte_array;
		}
		
		public function getPunteggioCorrezione(CorrezioneDomanda $correzioneDomanda): float {
			$punteggio = 0;
			if(!$correzioneDomanda->parziale) {
				$valori = [];
				if ($correzioneDomanda->valore != "") {
					$valori = explode(",", $correzioneDomanda->valore);
				}
				$risposte = $this->getRisposte();
				$risposte_corrette = $this->getRisposteCorrette(false);
				
				$error = false;
				foreach ($risposte as $risposta) {
					if (in_array($risposta["ID"], $valori) && !$risposta["corretto"]) {
						$error = true;
					} else if (in_array($risposta["ID"], $valori) && $risposta["corretto"]) {
						$punteggio += $this->punteggio / count($risposte_corrette);
					}
				}
				if ($error)
					$punteggio = 0;
			}else{
				return $correzioneDomanda->punteggio;
			}
			return $punteggio;
		}
		
		static function getUltimoOrdineRisposta(int $id_rispostamultipla): int {
			global $mysql;
			$mysql->escape($id_rispostamultipla);
			return $mysql->select(self::$sqlTableRisposte, "ID_rispostamultipla='$id_rispostamultipla'", "COALESCE(MAX(ordine), 0) as max_ordine")->fetch_assoc()["max_ordine"];
		}
		
		static function getRisposta(int $id): array {
			global $mysql;
			$mysql->escape($id);
			
			$risposta = $mysql->select(static::$sqlTableRisposte, "ID='$id'");
			$row = mysqli_fetch_assoc($risposta);
			if (gettype($row) == "array") {
				return $row;
			}
			
			return [];
		}
	}