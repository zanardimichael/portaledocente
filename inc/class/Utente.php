<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/utils.php";

class Utente {

	public int $id;
	public string $username;
	public string $password;
	public string $nome;
	public string $cognome;
	public string $email;
	public string $session;

	static $sqlNames = [
		"id" => "id",
		"username" => "username",
		"password" => "password",
		"nome" => "nome",
		"cognome" => "cognome",
		"email" => "email",
		"session" => "session",
	];

	public function __construct(int $id, $sql = null) {
		$this->id = $id;
		if($sql != null){
			$this->getData($sql);
		}
	}

	static function createData($data) {
		global $connection;
		$keys = "";
		$values = "";
		foreach($data as $key => $value){
			if(!array_search($key, Utente::$sqlNames)){
				return null;
			}
			$keys .= "$key,";
			$values .= "'".mysqli_escape_string($connection, $value)."',";
		}
		$keys = substr($keys, 0, strlen($keys)-1);
		$values = substr($values, 0, strlen($values)-1);
		mysqli_query($connection, "INSERT INTO utente ($keys) VALUES ($values)");
		echo mysqli_error($connection);
	}

	static function saveData($data) {
		global $connection;
		$keys = "";
		foreach($data as $key => $value){
			if(!array_search($key, Utente::$sqlNames)){
				return null;
			}elseif($key != "id"){
				$keys .= $key."='".mysqli_escape_string($connection, $value)."',";
			}
		}
		$keys = substr($keys, 0, strlen($keys)-1);
		mysqli_query($connection, "UPDATE utente SET $keys WHERE id='$data[id]'");
		echo mysqli_error($connection);
	}

	public function getData($sql = "*"){
		global $connection;
		$sqlSearch = "";
		if($sql != "*"){
			foreach($sql as $element){
				if(!array_search($element, Utente::$sqlNames)){
					return null;
				}
				$sqlSearch .= $element.",";
			}
			$sqlSearch = substr($sqlSearch, 0, strlen($sqlSearch)-1);
		}else{
			$sqlSearch = $sql;
		}

		$result = mysqli_query($connection, "SELECT $sqlSearch FROM utente WHERE id='$this->id'");
		if(mysqli_error($connection) != null) return null;
		$row = mysqli_fetch_assoc($result);
		foreach($row as $key => $value){
			$this->$key = $value;
		}
	}
	
	public function getNomeCognome() {
		return $this->nome.($this->cognome != "" ? " ".$this->cognome: "");
	}

	// STATICS

	static function verifyLogin() {
		global $connection;
		if(!isset($_COOKIE["user_session"])) return false;
		$cookieAuth = $_COOKIE["user_session"];
		$result = mysqli_query($connection, "SELECT id FROM utente WHERE session='$cookieAuth'");
		if(mysqli_num_rows($result) > 0){
			return mysqli_fetch_assoc($result)["id"];
		}
		return false;
	}

	static function verifyUserLogin($username, $password){
		global $connection;
		$username = mysqli_escape_string($connection, $username);
		$password = hash("sha512", mysqli_escape_string($connection, $password));
		$result = mysqli_query($connection, "SELECT id FROM utente WHERE username='$username' AND password='$password'");
		if(mysqli_error($connection) != null) return mysqli_error($connection);
		if(mysqli_num_rows($result) > 0){
			$id = mysqli_fetch_assoc($result)["id"];
			$utente = new Utente($id, "*");
			$session_id = uuidv4();
			setcookie("user_session", $session_id, time()+86400*30, "/");
			$utente->saveData(["id" => $id, "session" => $session_id]);
			return true;
		}
		return false;
	}

	static function deleteData($id) {
		global $connection;
		mysqli_query($connection, "DELETE FROM utente WHERE id='$id'");
		echo mysqli_error($connection);
	}

	static function getAll($sql = "*"){
		global $connection;
		$result = mysqli_query($connection, "SELECT id FROM utente");
		if(mysqli_error($connection) != null) return null;

		$canzoni = array();
		while($row = mysqli_fetch_assoc($result)) {
			array_push($canzoni, new Utente($row["id"], $sql));
		}
		return $canzoni;
	}
}