<?php
	
	require_once "./inc/config.php";
	require_once "./inc/utils.php";
	require_once "./inc/mysql.php";
	require_once "./inc/class/Verifica.php";
	
	$userId = Utente::verifyLogin();
	if (!$userId) {
		header("Location: /login.php");
	}
	
	$utente = new Utente($userId, "*");
	$current_prof = Professore::getProfessoreByUtenteID($userId);
	
	if(verifyAllGetVars(["type", "id"])){
		switch($_GET["type"]){
			case "verifica_latex":
				$verifica = new Verifica($_GET["id"]);
				
				if($verifica->professore->id != $current_prof->id){
					header("Location: /");
					exit();
				}
				header("Content-Type: text/plain");
				header('Content-Disposition: attachment; filename="'.$verifica->titolo.' - '.$verifica->classe->getNomeClasse().'.tex"');
				
				echo $verifica->getLatex();
				exit();
		}
	}
	header("Location: /");