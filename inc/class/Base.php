<?php

class Base {

	public $sqlNames = [];
	public $sqlTable = "";

	public function getData($sql = "*"){
		global $connection;
		$sqlSearch = "";
        if($sql == "id") {
            $sqlSearch = "id";
        }elseif($sql != "*"){
			foreach($sql as $element){
				if(!array_search($element, $this->sqlNames)){
					return null;
				}
				$sqlSearch .= $element.",";
			}
			$sqlSearch = substr($sqlSearch, 0, strlen($sqlSearch)-1);
		}else{
			$sqlSearch = $sql;
		}

		$result = mysqli_query($connection, "SELECT $sqlSearch FROM $this->sqlTable WHERE id='$this->id'");
		if(mysqli_error($connection) != null) return null;
		$row = mysqli_fetch_assoc($result);
		foreach($row as $key => $value){
			$this->$key = $value;
		}
	}

	public function setData(string $name, string $value): bool{
		global $connection;

		mysqli_query($connection, "UPDATE $this->sqlTable SET $name='$value' WHERE id='$this->id'");
		if(mysqli_error($connection) != null) return false;
		return true;
	}


}