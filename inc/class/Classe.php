<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	
	class Classe extends Base {
		
		static $sqlNames = [
			"id",
			"anno",
			"classe",
			"sezione",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "classe";
		
		public int $id;
		public int $anno;
		public int $classe;
		public string $sezione;
		public ?string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			if ($id != null) {
				$this->id = $id;
				$this->getData($sql);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string $sql
		 * @return Classe[]
		 */
		static function getAll(bool $object = false, string $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Classe($row["id"], $sql) : $array[] = $row["id"];
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
	}