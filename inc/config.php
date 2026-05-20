<?php

	$prod = true;
	$debug = true;
	$api = false;
	$versione = "0.9.1";

	if($debug){
		ini_set("display_errors", "1");
		error_reporting(E_ERROR);
	}
	
	require "mysql_config.php";