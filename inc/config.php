<?php

	$prod = true;
	$debug = true;

	$versione = "0.4.0";

	if($debug){
		ini_set("display_errors", "1");
		error_reporting(E_ERROR);
	}

	$db_host = "localhost";
	$db_user = "portaledocentedbuser";
	$db_pass = "FxGkrjDlJ3WoCO4Lv396";
	$db_schema = "portaledocente";
	