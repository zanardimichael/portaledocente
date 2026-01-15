<?php
	
	require_once 'Correzione.php';
	require_once 'Alunno.php';
	
	class CorrezioneDomanda extends Base {
		
		public int $ID;
		public int $ID_correzione;
		public Correzione $correzione {
			get {
				return new Correzione($this->ID_correzione);
			}
		}
		
		public int $ID_alunno;
		public Alunno $alunno {
			get {
				return new Alunno($this->ID_alunno);
			}
		}
		
		public int $ID_esercizio;
		public string $tipologia_esercizio;
		public bool $parziale;
		public float $punteggio;
		public string $valore;
		public string $note;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		static $sqlTable = "correzione_domanda";
		
		static $sqlNames = [
			"ID",
			"ID_correzione",
			"ID_alunno",
			"ID_esercizio",
			"tipologia_esercizio",
			"parziale",
			"valore",
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
		 * @return CorrezioneDomanda[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*"): array {
			global $mysql;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new CorrezioneDomanda($row["id"], $sql) : $array[] = $row["id"];
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
		
		public function render(): string {
			$esercizio = new $this->tipologia_esercizio($this->ID_esercizio);
			return $esercizio->renderCorrezione($this);
		}
	}