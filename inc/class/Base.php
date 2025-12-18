<?php
	
	require_once "MYSQLHandler.php";

abstract class Base {

	static $sqlNames = [];
	static $sqlTable = "";

	public function getData($sql = "*"){
		global $connection;
		$mysql = new MYSQLHandler($connection);
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
		global $connection;
		$mysql = new MYSQLHandler($connection);
		
		$mysql->update(static::$sqlTable, "id='$this->id'", [$name => $value]);
		if(mysqli_error($connection) != null) return false;
		return true;
	}


}