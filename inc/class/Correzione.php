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
		
		static $sqlTableAlunni = "correzione_alunno";
		
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
		
		public function getVotoAlunno(int $ID_alunno) : int {
			global $mysql;
			$punteggio_verifica = $this->verifica->getPunteggioVerifica();
			$punteggio_alunno = $this->getPunteggioAlunno($ID_alunno);
			
			return floor($punteggio_alunno / $punteggio_verifica * 20) / 2;
		}
		
		public function getPunteggioAlunno(int $ID_alunno) : float {
			global $mysql;
			$punteggio = 0;
			
			$result = $mysql->select(CorrezioneDomanda::$sqlTable, "ID_correzione='$this->ID' AND ID_alunno='$ID_alunno'");
			while($row = mysqli_fetch_assoc($result)){
				$domanda = new CorrezioneDomanda($row["ID"]);
				$punteggio += $domanda->getPunteggio();
			}
			return $punteggio;
		}
		
		/**
		 * @param bool $object
		 * @return int[]|Alunno[]
		 */
		public function getAlunni(bool $object = true): array {
			global $mysql;
			$alunni = [];
			
			$return = $mysql->select(static::$sqlTableAlunni, "ID_correzione='$this->ID'", "ID_alunno");
			
			while($row = mysqli_fetch_assoc($return)){
				$alunni[] = $object ? new Alunno($row["ID_alunno"]) : $row["ID_alunno"];
			}
			
			return $alunni;
		}
		
		/**
		 * @param bool $object
		 * @return int[]|Alunno[]
		 */
		public function getAlunniNonSelezionati(bool $object = true): array {
			global $mysql;
			$alunni = [];
			$classe = $this->verifica->classe;
			$alunni_selezionati = $this->getAlunni(false);
			$alunni_selezionati_sql = implode(", ", $alunni_selezionati);
			$sql = "ID NOT IN ($alunni_selezionati_sql) AND ID_classe='$classe->id'";
			if($alunni_selezionati_sql == ""){
				$sql = "ID_classe='$classe->id'";
			}
			
			$return = $mysql->select(Alunno::$sqlTable, $sql, "ID");
			
			while($row = mysqli_fetch_assoc($return)){
				$alunni[] = $object ? new Alunno($row["ID"]) : $row["ID"];
			}
			
			return $alunni;
		}
		
		public function addAlunni(array $ids): bool {
			global $mysql;
			$values = "";
			foreach($ids as $ID_alunno){
				$values .= " ('$this->ID', '$ID_alunno'),";
			}
			$values = substr($values, 0, -1);
			
			return $mysql->insert(static::$sqlTableAlunni, "(ID_correzione, ID_alunno) VALUES ".$values);
		}
		
		public function getNextAlunno(int $ID_alunno): Alunno|false {
			$alunni = $this->getAlunni(false);
			
			if(count($alunni) == 1){
				return false;
			}else{
				$index = array_search($ID_alunno, $alunni);
				if($index !== false){
					if($index != count($alunni) - 1){
						return new Alunno($alunni[$index + 1]);
					}
				}
			}
			return false;
		}
		
		public function getPreviousAlunno(int $ID_alunno): Alunno|false {
			$alunni = $this->getAlunni(false);
			
			if(count($alunni) == 1){
				return false;
			}else{
				$index = array_search($ID_alunno, $alunni);
				if($index !== false){
					if($index != 0){
						return new Alunno($alunni[$index - 1]);
					}
				}
			}
			return false;
		}
	}