<?php
	
	require_once "Verifica.php";
	
	class Correzione extends Base {
		
		public int $ID;
		public int $ID_verifica;
		public Verifica $verifica {
			get {
				return new Verifica($this->ID_verifica);
			}
		}
		public string $data_verifica;
		public string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		static $sqlTable = "correzione";
		static $sqlNames = [
			"ID_verifica",
			"data_verifica",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		public function __construct(?int $id, array|string $sql = "*") {
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Correzione[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Correzione($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		static function create($data): int|string|false {
			global $mysql;
			if($mysql->insert(static::$sqlTable, $data))
				return $mysql->getInsertId();
			return false;
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
		
		public function getDataVerifica($formato = "d/m/Y"): string {
			return date($formato, strtotime($this->data_verifica));
		}
		
		public function getTimestampDataVerifica() : string {
			return strtotime($this->data_verifica);
		}
	}