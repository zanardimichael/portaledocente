<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/CorrezioneDomanda.php';
	
	class Verofalso extends Base {
		
		static $sqlNames = [
			"id",
			"ID_verifica",
			"ID_sezione",
			"testo",
			"risultato",
			"note",
			"punteggio",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica_verofalso";
		
		public int $id;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public ?int $ID_sezione;
		public Sezione $sezione;
		public string $testo;
		public bool $risultato;
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
		 * @return Verofalso[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Verofalso($row["id"], $sql) : $array[] = $row["id"];
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
			$verofalso = new Verofalso($id, ["ordine", "ID_sezione"]);
			$delete = $mysql->delete(static::$sqlTable, "ID='$id'");
			Sezione::updateOrdineEsercizi($verofalso->ordine, $verofalso->ID_sezione);
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
				<div class="col-12 esercizio" id="esercizio-'.$this->id.'" id-verofalso="'.$this->id.'" ordine="'.$this->ordine.'">
					<div class="card card-success card-outline">
						<div class="card-header">
							<div class="card-title">
								Vero-Falso
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary modifica-verofalso" id-verofalso="'.$this->id.'">Modifica</button>
									<button type="button" class="btn btn-sm btn-danger elimina-verofalso" id-verofalso="'.$this->id.'">Elimina</button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-giu-esercizio" '.$disabled_giu.' id-verofalso="'.$this->id.'"><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-su-esercizio" '.$disabled_su.' id-verofalso="'.$this->id.'"><i class="bi bi-chevron-up"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<h5>'.$this->testo.'</h5>
							<div>Risultato: '.($this->risultato ? "Vero": "Falso").'</div>
							<hr>
							<div>Punteggio: '.$this->punteggio.'</div>
						</div>
					</div>
				</div>';
		}
		
		public function renderCorrezione(CorrezioneDomanda $correzioneDomanda): string {
			$risposta_prevista = $this->risultato ? "Vero" : "Falso";
			
			$card_info = "card-info";
			if($correzioneDomanda->valore != "") {
				$card_info = intval($correzioneDomanda->valore) == $this->risultato ? "card-success" : "card-danger";
			}
			$checked_vero = $correzioneDomanda->valore == "1" ? "checked" : "";
			$checked_falso = $correzioneDomanda->valore == "0" ? "checked" : "";
			
			$punteggio_parziale = "0";
			$checked_parziale = "";
			if($correzioneDomanda->parziale){
				$checked_parziale = "checked";
				$punteggio_parziale = $correzioneDomanda->punteggio;
			}
			
			$punteggio = 0;
			if($card_info == "card-success"){
				$punteggio = $this->punteggio;
				if($correzioneDomanda->parziale != ""){
					$punteggio = $correzioneDomanda->punteggio;
				}
			}
			$input_punteggio_parziale = $correzioneDomanda->parziale ? "" : "disabled";
			
			return '<form action="/api/verofalso/correzione/'.$correzioneDomanda->id.'" id="'.$correzioneDomanda->id.'">
			<div class="card '.$card_info.' card-outline verofalso mb-2" id="correzione-'.$correzioneDomanda->id.'" id-verofalso="'.$this->id.'">
				<div class="card-header">
					Vero-Falso
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12 col-md-6 mb-2">
							<p>'.$this->testo.'</p>
							<label class="form-label">Risposta</label>
							<div class="form-check">
								<input class="form-check-input verofalso-radio" type="radio" name="valore" id="vero-verofalso-'.$this->id.'" value="1" '.$checked_vero.'>
								<label class="form-check-label" for="vero-verofalso-'.$this->id.'">
									Vero
								</label>
							</div>
							<div class="form-check">
								<input class="form-check-input verofalso-radio" type="radio" name="valore" id="falso-verofalso-'.$this->id.'" value="0" '.$checked_falso.'>
								<label class="form-check-label" for="falso-verofalso-'.$this->id.'">
									Falso
								</label>
							</div>
							<div class="mt-2">Risposta prevista: '.$risposta_prevista.'</div>
						</div>
						<div class="col-sm-12 col-md-6 mb-2">
							<label class="form-label">Parziale</label>
							<div class="form-check">
								<input class="form-check-input parziale" name="parziale" type="checkbox" value="1" id="parziale-verofalso-'.$this->id.'" '.$checked_parziale.'>
								<label class="form-check-label" for="parziale-verofalso-'.$this->id.'">
									Specifica punteggio parziale
								</label>
							</div>
							<hr>
							<label class="form-label" for="punteggio-parziale-verofalso-'.$this->id.'">Punteggio Parziale</label>
							<input
								type="number"
								class="form-control punteggio-parziale"
								id="punteggio-parziale-verofalso-'.$this->id.'"
								name="punteggio"
								value="'.$punteggio_parziale.'"
								min="0"
								step="0.1"
								max="64"
								required
								'.$input_punteggio_parziale.'
							/>
						</div>
					</div>
				</div>
				<div class="card-footer">
					Punteggio: <span class="risulato-verofalso-'.$this->id.'">'.$punteggio.'</span>
				</div>
			</div>
			</form>';
		}
		
		public function renderLatex(): string{
			return "\n$this->testo\hfill Vero - Falso \\\\";
		}
	}