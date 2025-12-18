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
		 * @param $object
		 * @param $sql
		 * @return Classe[]
		 */
		static function getAll($object = false, $sql = "*"){
			global $connection;
			$mysql = new MYSQLHandler($connection);
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Classe($row["id"]) : $array[] = $row["id"];
			}
			return $array;
		}
		
		public function getTimestampCreazioneTime() : int {
			return strtotime($this->timestamp_creazione);
		}
		
		public function getTimestampModificaTime() : int {
			return strtotime($this->timestamp_modifica);
		}
	}