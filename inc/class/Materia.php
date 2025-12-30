<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	
	class Materia extends Base {
		
		static $sqlNames = [
			"ID",
			"nome",
			"note",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "materia";
		static $sqlTableMateriaProfessoreClasse = "materia_professore_classe";
		
		public int $id;
		public string $nome;
		public string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, array|string $sql = "*") {
			parent::__construct($id, $sql);
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Materia[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Materia($row["id"], $sql) : $array[] = $row["id"];
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
		
		/**
		 * @param int $ID_classe
		 * @param int $ID_professore
		 * @param string|array $sql
		 * @param bool $object
		 * @return array|Materia[]
		 */
		public static function getMaterieClasseProfessore(int $ID_classe, int $ID_professore, string|array $sql = "*", bool $object = true): array {
			global $mysql;
			$materie = [];
			$result = $mysql->select(static::$sqlTableMateriaProfessoreClasse, "ID_classe='$ID_classe' AND ID_professore='$ID_professore'", "ID_materia");
			
			while($row = mysqli_fetch_assoc($result)){
				$materie[] = $object ? new Materia($row["ID_materia"], $sql): $row["ID_materia"];
			}
			return $materie;
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
			$result = $mysql->select(static::$sqlTableMateriaProfessoreClasse, "ID_materia='$this->id'", "DISTINCT ID_classe");
			while($row = mysqli_fetch_assoc($result)){
				$classi[] = $object ? new Classe($row["ID_classe"]) : $row["ID_classe"];
			}
			return $classi;
		}
		
		/**
		 * @return array
		 */
		public function getClassiProfessori(): array {
			global $mysql;
			$classi = [];
			$result = $mysql->select(static::$sqlTableMateriaProfessoreClasse, "ID_materia='$this->id'", ["ID_classe", "ID_professore"]);
			while($row = mysqli_fetch_assoc($result)){
				$classi[$row["ID_classe"]][] = $row["ID_professore"];
			}
			return $classi;
		}
	}