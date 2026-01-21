<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	
	class RispostaAperta extends Base {
		
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
		
		static $sqlTable = "verifica_rispostaaperta";
		
		public int $id;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public ?int $ID_sezione;
		public Sezione $sezione;
		public string $testo;
		public ?string $note;
		public int $punteggio;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
			if(isset($this->ID_verifica)){
				$this->verifica = new Verifica($this->ID_verifica);
			}
			if(isset($this->ID_sezione)){
				$this->sezione = new Sezione($this->ID_sezione);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return RispostaAperta[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new RispostaAperta($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		static function create($data): bool{
			global $mysql;
			return $mysql->insert(static::$sqlTable, $data);
		}
		
		static function edit($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTable, "ID='$id'", $data);
		}
		
		static function delete($id): bool{
			global $mysql;
			$rispostaaperta = new RispostaAperta($id, ["ordine", "ID_sezione"]);
			$delete = $mysql->delete(static::$sqlTable, "ID='$id'");
			Sezione::updateOrdineEsercizi($rispostaaperta->ordine, $rispostaaperta->ID_sezione);
			return $delete;
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
			
			return '
				<div class="col-12 esercizio" id="esercizio-'.$this->id.'" id-rispostaaperta="'.$this->id.'" ordine="'.$this->ordine.'">
					<div class="card card-success card-outline">
						<div class="card-header">
							<div class="card-title">
								Risposta aperta
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary modifica-rispostaaperta" id-rispostaaperta="'.$this->id.'">Modifica</button>
									<button type="button" class="btn btn-sm btn-danger elimina-rispostaaperta" id-rispostaaperta="'.$this->id.'">Elimina</button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-giu-esercizio" '.$disabled_giu.' id-rispostaaperta="'.$this->id.'"><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-su-esercizio" '.$disabled_su.' id-rispostaaperta="'.$this->id.'"><i class="bi bi-chevron-up"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<h5>'.$this->testo.'</h5>
							<div>Punteggio: '.$this->punteggio.'</div>
						</div>
					</div>
				</div>';
		}
		
		public function renderLatex(): string {
			return "\n\\textbf{Rispondi alle domanda:} \\\\\n$this->testo";
		}
		
		public function renderCorrezione(CorrezioneDomanda $correzioneDomanda): string {
			$checked_corretto = $correzioneDomanda->valore ? "checked" : "";
			$checked_errato = $correzioneDomanda->valore == 0 ? "checked" : "";
			
			$punteggio = 0;
			if($correzioneDomanda->valore) $punteggio = $this->punteggio;
			if($correzioneDomanda->parziale){
				$punteggio = $correzioneDomanda->punteggio;
			}
			
			$checked_parziale = $correzioneDomanda->parziale ? "checked" : ""; // Se parziale è selezionato la checkbox deve essere selezionata
			$punteggio_parziale = $correzioneDomanda->parziale ? $correzioneDomanda->punteggio : ""; // Se parziale è selezionato il punteggio deve essere inserito nell'input
			$input_punteggio_parziale = $correzioneDomanda->parziale ? "" : "disabled"; // Se parziale è selezionato l'input deve essere attivo altrimenti deve essere disabilitato
			
			$card_info = "card-warning";
			if($correzioneDomanda->valore == ""){
				$card_info = "card-info";
			}else if($punteggio == $this->punteggio){
				$card_info = "card-success";
			}else if($punteggio == 0){
				$card_info = "card-danger";
			}
			
			return '<form action="/api/rispostaaperta/correzione/'.$correzioneDomanda->id.'" id="'.$correzioneDomanda->id.'">
				<div class="card '.$card_info.' card-outline mb-2" id="correzione-'.$correzioneDomanda->id.'">
					<div class="card-header">
						Risposta Aperta
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
								<p>'.$this->testo.'</p>
								<label class="form-label">Risposta</label>
								<div class="form-check">
									<input class="form-check-input verofalso-radio" type="radio" name="valore" id="vero-risposta-aperta-'.$this->id.'" value="1" '.$checked_corretto.'>
									<label class="form-check-label" for="vero-risposta-aperta-'.$this->id.'">
										Corretta (punteggio pieno)
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input verofalso-radio" type="radio" name="valore" id="falso-risposta-aperta-'.$this->id.'" value="0" '.$checked_errato.'>
									<label class="form-check-label" for="falso-risposta-aperta-'.$this->id.'">
										Completamente Errata (0 punti)
									</label>
								</div>
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label class="form-label">Parziale</label>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="1" name="parziale" id="parziale-risposta-aperta-'.$this->id.'" '.$checked_parziale.'>
									<label class="form-check-label" for="parziale-risposta-aperta-'.$this->id.'">
										Specifica punteggio parziale
									</label>
								</div>
								<hr>
								<label class="form-label" for="punteggio-parziale-risposta-aperta-'.$this->id.'">Punteggio Parziale</label>
								<input
										type="number"
										class="form-control"
										id="punteggio-parziale-risposta-aperta-'.$this->id.'"
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
						Punteggio: <span class="risulato-risposta-aperta-'.$this->id.'">'.$punteggio.'</span>
					</div>
				</div></form>';
		}
	}