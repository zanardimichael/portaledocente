<?php /** @noinspection PhpComposerExtensionStubsInspection */
	
	require_once "config.php";
require_once "class/MySQLHandler.php";
global $db_host, $db_user, $db_pass, $db_schema;

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_schema);
$mysql = new MySQLHandler($connection);

