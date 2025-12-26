<?php
	
	require_once "MySQLHandler.php";

abstract class Base {

	static $sqlNames = [];
	static $sqlTable = "";
	
	public int $id;
	
	public function __construct(?int $id, string|array $sql = "*") {
		if ($id != null) {
			$this->id = $id;
			$this->getData($sql);
		}
	}
	
	public function getData($sql = "*"){
		global $connection;
		$mysql = new MySQLHandler($connection);
		$sqlSearch = "";
        if($sql == "id") {
            $sqlSearch = "id";
        }elseif($sql != "*"){
			foreach($sql as $element){
				if(!array_search($element, static::$sqlNames)){
					return null;
				}
				$sqlSearch .= $element.",";
			}
			$sqlSearch = substr($sqlSearch, 0, strlen($sqlSearch)-1);
		}else{
			$sqlSearch = $sql;
		}

		$result = $mysql->select(static::$sqlTable, "id='$this->id'", $sqlSearch);
		if(mysqli_error($connection) != null) return null;
		$row = mysqli_fetch_assoc($result);
		foreach($row as $key => $value){
			$this->$key = $value;
		}
	}

	public function setData(string $name, string $value): bool{
		global $mysql;
		
		return $mysql->update(static::$sqlTable, "id='$this->id'", [$name => $value]);
	}

	static function exists(int $id): bool{
		global $mysql;
		
		return $mysql->select(static::$sqlTable, "id='$id'", "COUNT(ID) as ids")->fetch_assoc()["ids"] == 1;
	}

}