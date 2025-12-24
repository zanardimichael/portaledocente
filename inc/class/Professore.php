<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Utente.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Materia.php";
	
	class Professore extends Base {
		
		static $sqlNames = [
			"ID",
			"ID_utente",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "professore";
		
		public int $id;
		public int $ID_utente;
		public string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, array|string $sql = "*") {
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Professore[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Professore($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		public function __get(string $name) {
			if($name == "utente"){
				return new Utente($this->ID_utente, "*");
			}
			return null;
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
		
		/**
		 * @param bool $object
		 * @return Classe[]|int[]
		 */
		public function getClassi(bool $object = false): array {
			global $mysql;
			$classi = [];
			$result = $mysql->select("materia_professore_classe", "ID_professore='$this->id'", "DISTINCT ID_classe");
			while($row = mysqli_fetch_assoc($result)){
				$classi[] = $object ? new Classe($row["ID_classe"]) : $row["ID_classe"];
			}
			return $classi;
		}
		
		/**
		 * @return array
		 */
		public function getClassiMaterie(): array {
			global $mysql;
			$classi = [];
			$result = $mysql->select("materia_professore_classe", "ID_professore='$this->id'", ["ID_classe", "ID_materia"]);
			while($row = mysqli_fetch_assoc($result)){
				$classi[$row["ID_classe"]][] = $row["ID_materia"];
			}
			return $classi;
		}
	}