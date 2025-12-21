<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	
	class Alunno extends Base {
		
		static $sqlNames = [
			"id",
			"ID_classe",
			"nome",
			"cognome",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "alunno";
		
		public int $id;
		public int $ID_classe;
		public string $nome;
		public string $cognome;
		public string $email;
		public ?string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct($id, $sql = "*") {
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string $sql
		 * @return Alunno[]
		 */
		static function getAll(bool $object = false, string $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Alunno($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}

		public function __get(string $name) {
			if($name == "classe"){
				return (new Classe($this->ID_classe));
			}
			return $this->$name;
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
		
		public function getNomeCognome() : string {
			return $this->nome." ".$this->cognome;
		}
	}