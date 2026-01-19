<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	
	class Alunno extends Base {
		
		static $sqlNames = [
			"id",
			"ID_classe",
			"numero_registro",
			"nome",
			"cognome",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "alunno";
		
		public int $id;
		public int $ID_classe;
		public int $numero_registro;
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
		
		public function getNextAlunno(): false|Alunno {
			if(self::getLastAlunnoClasse($this->ID_classe, false) == $this->id){
				return false;
			}
			global $mysql;
			
			$alunno = false;
			$numero_registro = $this->numero_registro;
			do {
				$numero_registro++;
				$result = $mysql->select(static::$sqlTable, "numero_registro=$numero_registro", "ID");
				if($result->num_rows > 0){
					return new Alunno($result->fetch_object()->ID);
				}
				if($numero_registro > 40){
					$alunno = true;
					return false;
				}
			} while(!$alunno);
			return false;
		}
		
		public function getPreviousAlunno(): false|Alunno {
			if(self::getFirstAlunnoClasse($this->ID_classe, false) == $this->id){
				return false;
			}
			global $mysql;
			
			$alunno = false;
			$numero_registro = $this->numero_registro;
			do {
				$numero_registro--;
				$result = $mysql->select(static::$sqlTable, "numero_registro=$numero_registro", "ID");
				if($result->num_rows > 0){
					return new Alunno($result->fetch_object()->ID);
				}
				if($numero_registro == 0){
					$alunno = true;
					return false;
				}
			} while(!$alunno);
			return false;
		}
		
		static function getLastAlunnoClasse(int $ID_classe, $object = true) : int|Alunno {
			global $mysql;
			
			if(Classe::exists($ID_classe)) {
				$result = $mysql->select(static::$sqlTable, "ID_classe=$ID_classe ORDER BY numero_registro DESC LIMIT 1", "ID");
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
					return $object ? new Alunno($row["ID"]) : $row["ID"];
				}
			}
			return 0;
		}
		
		static function getFirstAlunnoClasse(int $ID_classe, $object = true) : int|Alunno {
			global $mysql;
			
			if(Classe::exists($ID_classe)) {
				$result = $mysql->select(static::$sqlTable, "ID_classe=$ID_classe ORDER BY numero_registro ASC LIMIT 1", "ID");
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
					return $object ? new Alunno($row["ID"]) : $row["ID"];
				}
			}
			return 0;
		}
	}