<?php
	
	class MySQLHandler {
		
		public mysqli $connection;
		
		public function __construct($connection) {
			$this->connection = $connection;
		}
		
		/**
		 * @param string $table
		 * @param string $where
		 * @param string|array $data data può essere una stringa tipo "id, nome, cognome" oppure un array di stringhe ["id", "nome","cognome"] oppure un array associativo di stringhe ["id" => "id", "nome" => "name", "cognome" => "surname"]
		 * successivamente verrà convertito con as: "id as id, nome as name, cognome as surname
		 * @return mysqli_result|false
		 */
		public function select(string $table, string $where = "", string|array $data = "*"): mysqli_result|false {
			$data_required = $data;
			if($data != "*"){
				if(gettype($data) == "array"){
					if(array_keys($data) !== range(0, count($data) - 1)){
						foreach($data as $key => $value){
							$data_required = $this->connection->real_escape_string($key)." as ".$this->connection->real_escape_string($value).", ";
						}
						$data_required = substr($data_required, 0, -2);
					}else{
						$data_required = implode(",", $data);
					}
				}
			}
			$sql = "SELECT $data_required FROM $table ".($where == "" ? "" : "WHERE $where");
			$result = $this->query($sql);
			if($this->connection->error){
				$this->logError($this->connection->error, $this->connection->errno, $sql);
				return false;
			}
			return $result;
		}
		
		/**
		 * @param string $table
		 * @param string $where
		 * @param string|array $data può essere un array associativo: ["nome" => "Mario", "cognome" => "Rossi"] oppure direttamente una stringa
		 * @return bool
		 */
		public function update(string $table, string $where, array|string $data, bool $escape = true): bool|mysqli_result {
			$data_update = "";
			if(gettype($data) == "array") {
				foreach ($data as $key => $value) {
					$data_update .= $this->connection->real_escape_string($key) . "='" . $this->connection->real_escape_string($value) . "', ";
				}
				$data_update = substr($data_update, 0, -2);
			}else{
				$data_update = $escape ? $this->connection->real_escape_string($data): $data;
			}
			$result = $this->query("UPDATE $table SET $data_update WHERE $where");
			if($this->connection->error){
				$this->logError($this->connection->error, $this->connection->errno);
				return false;
			}
			return $result;
		}
		
		/**
		 * @param string $table
		 * @param array|string $data deve essere un array associativo: ["nome" => "Mario", "cognome" => "Rossi"] oppure una stringa già comprendente (colonne) VALUES (...)
		 * @return int|false se diverso da falso ritorna l'id della nuova riga
		 */
		public function insert(string $table, array|string $data): int|false {
			if(gettype($data) == "array") {
				$names_array = [];
				$values_array = [];
				foreach ($data as $key => $value) {
					$names_array[] = $this->connection->real_escape_string($key);
					$values_array[] = "'" . $this->connection->real_escape_string($value) . "'";
				}
				$names = implode(",", $names_array);
				$values = implode(",", $values_array);
				
				$this->query("INSERT INTO $table ($names) VALUES ($values)");
			}else {
				$this->query("INSERT INTO $table $data");
			}
			
			if($this->connection->error){
				$this->logError($this->connection->error, $this->connection->errno);
				return false;
			}
			return $this->connection->insert_id;
		}
		
		/**
		 * @param string $table
		 * @param string $where
		 * @return bool
		 */
		public function delete(string $table, string $where): bool {
			$result = $this->query("DELETE FROM $table WHERE $where");
			if($this->connection->error){
				$this->logError($this->connection->error, $this->connection->errno);
				return false;
			}
			return $result;
		}
		
		public function escape(&$string): void {
			$string = $this->connection->real_escape_string($string);
		}
		
		public function query($query) {
			return $this->connection->query($query);
		}
		
		public function logError($error, $errno, $vars = "") {
			$vars = $this->connection->real_escape_string($vars);
			$this->query("INSERT INTO log_error (errorCode, errorMessage, errorStackTrace, requestVars) VALUES ('$error', '$errno', '', '$vars') ");
		}
		
		public function getInsertId(): int|string {
			return $this->connection->insert_id;
		}
	}