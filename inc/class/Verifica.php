<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Professore.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Materia.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Sezione.php";
	
	class Verifica extends Base {
		
		static $sqlNames = [
			"id",
			"ID_professore",
			"ID_materia",
			"ID_classe",
			"ID_verifica",
			"titolo",
			"note",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica";
		
		public int $id;
		public int $ID_professore;
		public Professore $professore;
		public int $ID_materia;
		public Materia $materia;
		public int $ID_classe;
		public Classe $classe;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public string $titolo;
		public ?string $note;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
			if(isset($this->ID_professore)){
				$this->professore = new Professore($this->ID_professore);
			}
			if(isset($this->ID_materia)){
				$this->materia = new Materia($this->ID_materia);
			}
			if(isset($this->ID_classe)){
				$this->classe = new Classe($this->ID_classe);
			}
			if(isset($this->ID_verifica)){
				$this->verifica = new Verifica($this->ID_verifica);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Verifica[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*", $professore = true): array {
			global $mysql;
			global $current_prof;
			$array = [];
			$result = $mysql->select(static::$sqlTable, $professore && $current_prof ? "ID_professore='".$current_prof->id."'": "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Verifica($row["id"], $sql) : $array[] = $row["id"];
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
		
		/**
		 * @param bool $object
		 * @return Sezione[]|int[]
		 */
		public function getSezioni(bool $object = true) : array {
			global $mysql;
			$array = [];
			
			$result = $mysql->select(Sezione::$sqlTable, "ID_verifica ORDER BY ordine ASC", "id");
			while($row = mysqli_fetch_assoc($result)){
				$array[] = $object ? new Sezione($row["id"]) : $row["id"];
			}
			return $array;
		}
	}