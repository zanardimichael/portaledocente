<?php
	function getReadableDateFromSqlTimestamp($sqlTimestamp) {
		return date("d/m/Y - H:i", strtotime($sqlTimestamp));
	}

	function uuidv4(){
		$data = random_bytes(16);

		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
			
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	function findObjectById($array, $id){
		foreach ( $array as $element ) {
			if ( $id == $element->id ) {
				return $element;
			}
		}
		return false;
	}
	
	function verifyAllPostVars(array $vars): bool{
		return array_all($vars, fn($var) => isset($_POST[$var]));
	}
	
	function verifyAllGetVars(array $vars): bool{
		return array_all($vars, fn($var) => isset($_GET[$var]));
	}

	function exception_handler($e): void {
		global $db_host, $db_user, $db_pass, $db_schema, $debug, $api, $page;
		$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_schema);

		$errorCode = mysqli_escape_string($connection, $e->getCode());
		$errorMessage = mysqli_escape_string($connection, $e->getMessage());
		$errorStackTrace = mysqli_escape_string($connection, $e->getTraceAsString());
		$get = implode(", ", array_keys($_GET));
		$post = implode(", ", array_keys($_POST));
		$requestVars = "GET: ".$get.", POST: ".$post;

		mysqli_query($connection, "INSERT INTO log_error (errorCode, errorMessage, errorStackTrace, requestVars) VALUES ('$errorCode', '$errorMessage', '$errorStackTrace', '$requestVars')");

		if($debug || $api) {
			print(json_encode([
				"errorCode" => $errorCode,
				"errorMessage" => $errorMessage,
				"errorStackTrace" => $errorStackTrace,
			]));
		}elseif($page instanceof PageHandler){
			$page->message->setMessage("Errore generico, si prega di contattare l'amministratore")->setMessageType(MessageType::Error)->show();
		}
	}

	set_exception_handler('exception_handler');
?>