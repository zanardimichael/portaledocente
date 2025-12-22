<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
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
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Classe[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
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
		
		public function getNomeClasse(): string {
			return $this->classe.$this->sezione;
		}
		
		public function getAlunni(bool $object = false): array {
			global $mysql;
			$alunni_array = [];
			$alunni = $mysql->select(Alunno::$sqlTable, "ID_classe='".$this->id."'", ["id"]);
			
			while($row = mysqli_fetch_assoc($alunni)){
				$alunni_array[] = $object ? new Alunno($row["id"]) : $row["id"];
			}
			return $alunni_array;
		}
		
		public function getNumeroAlunni(): int|false {
			global $mysql;
			$result = $mysql->select(Alunno::$sqlTable, "ID_classe='".$this->id."'", ["COUNT(id)" => "conto"]);
			
			while($row = mysqli_fetch_assoc($result)){
				return $row["conto"];
			}
			return false;
		}
		
		public function getAnnoScolastico() {
			return $this->anno."/".$this->anno+1;
		}
	}