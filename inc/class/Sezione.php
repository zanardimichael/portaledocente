<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Rispostamultipla.php";
	
	class Sezione extends Base {
		
		static $sqlNames = [
			"id",
			"ID_verifica",
			"titolo",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica_sezione";
		
		public int $id;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public string $titolo;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
			if(isset($this->ID_verifica)){
				$this->verifica = new Verifica($this->ID_verifica);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Sezione[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*", $professore = true): array {
			global $mysql;
			global $current_prof;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Sezione($row["id"], $sql) : $array[] = $row["id"];
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
		
		public function getDomande(): array {
			global $mysql;
			
			$array = [];
			$array["punteggio"] = 0;
			$verofalso = $mysql->select(Verofalso::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($verofalso)){
				$verofalso_object = new Verofalso($row["id"]);
				$array[$row["ordine"]] = $verofalso_object;
				$array["punteggio"] += $verofalso_object->punteggio;
			}
			
			$rispostamultipla = $mysql->select(Rispostamultipla::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($rispostamultipla)){
				$rispostamultipla_object = new Rispostamultipla($row["id"]);
				$array[$row["ordine"]] = $rispostamultipla_object;
				$array["punteggio"] += $rispostamultipla_object->punteggio;
			}
			
			return $array;
		}
	}