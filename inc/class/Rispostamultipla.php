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
		static function getAll(bool $object = false, string|array $sql = "*", $professore = true): array {
			global $mysql;
			global $current_prof;
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
		
		static function edit($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTable, "ID='$id'", $data);
		}
		
		static function delete($id): bool{
			global $mysql;
			return $mysql->delete(static::$sqlTable, "ID='$id'");
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
			
			foreach($risposte as $risposta){
				$risposte_txt .= '
					<div class="col-12" id-risposta="'.$risposta["id"].'">
						<div class="card">
							<div class="card-body p-2">
								<span class="mt-1 d-inline-block">'.$risposta["testo"].($risposta["corretto"] ? " <b>Corretta</b>": "").'</span>
								<div class="float-end">
									<div class="btn-group btn-group-sm">
										<button type="button" class="btn btn-sm btn-primary">Modifica</button>
										<button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-down"></i></button>
										<button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-up"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>';
			}
			
			return '
				<div class="col-12" id-rispostamultipla="'.$this->id.'">
					<div class="card card-success card-outline">
						<div class="card-header">
							<div class="card-title">
								Risposta multipla
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary">Modifica</button>
									<button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-chevron-up"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<h5>'.$this->testo.'</h5>
							<div>Punteggio: '.$this->punteggio.'</div>
							<hr/>
							<h5 class="mb-3 mt-2">
								Risposte
								<div class="float-end">
									<button type="button" class="btn btn-sm btn-primary">Aggiungi Risposta</button>
								</div>
							</h5>
							'.$risposte_txt.'
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
	}