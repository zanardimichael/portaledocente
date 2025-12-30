<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	
	class Rispostamultipla extends Base {
		
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
		 * @return Rispostamultipla[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Rispostamultipla($row["id"], $sql) : $array[] = $row["id"];
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
			$rispostamultipla = new Rispostamultipla($id, ["ordine", "ID_sezione"]);
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
		
		public function render(): string {
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
				<div class="col-12 esercizio" id-rispostamultipla="'.$this->id.'">
					<div class="card card-success card-outline">
						<div class="card-header">
							<div class="card-title">
								Risposta multipla
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary modifica-rispostamultipla" id-rispostamultipla="'.$this->id.'">Modifica</button>
									<button type="button" class="btn btn-sm btn-danger elimina-rispostamultipla" id-rispostamultipla="'.$this->id.'">Elimina</button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-giu-esercizio" id-rispostamultipla="'.$this->id.'"><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-su-esercizio" id-rispostamultipla="'.$this->id.'"><i class="bi bi-chevron-up"></i></button>
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
		
		public function getRisposte(): array{
			global $mysql;
			
			$risposte_array = [];
			$risposte = $mysql->select("verifica_rispostamultipla_risposte", "ID_rispostamultipla='$this->id'");
			while($row = mysqli_fetch_assoc($risposte)){
				$risposte_array[] = $row;
			}
			return $risposte_array;
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
			if(gettype($row) == "array") {
				return $row;
			}
			
			return [];
		}
	}