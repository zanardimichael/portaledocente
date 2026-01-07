<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	
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
				<div class="col-12 esercizio" id-verofalso="'.$this->id.'" ordine="'.$this->ordine.'">
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
		
		public function renderLatex(): string{
			return "\n$this->testo\hfill Vero - Falso \\\\";
		}
	}