<?php

	$prod = true;
	$debug = true;

	$versione = "0.6.4";

	if($debug){
		ini_set("display_errors", "1");
		error_reporting(E_ERROR);
	}
	
	require "mysql_config.php";