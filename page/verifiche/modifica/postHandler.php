<?php
	global $message;
	
	function modificaSezione(): void {
		global $message;
		// Se l'id è uguale a 0 aggiungo una nuova sezione
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["titolo", "ID_verifica"])) {
				$ordine = Sezione::getUltimoOrdineVerifica($_POST["ID_verifica"]) + 1;
				if(Sezione::create(["titolo" => $_POST["titolo"], "ordine" => $ordine, "ID_verifica" => $_POST["ID_verifica"]])){
					$message->setMessageType(MessageType::Success)
						->setMessage("Sezione creata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della sezione")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["titolo"])) {
				if(Sezione::edit($_POST["id"], ["titolo" => $_POST["titolo"]])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Sezione modificata correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio della sezione")
						->show();
				}
			}
		}
	}
	
	function eliminaSezione(): void {
		global $message;
		
		if(Sezione::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Sezione eliminata correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione della sezione")
				->show();
		}
	}
	
	function modificaVerofalso(): void {
		global $message;
		
		if($_POST["id"] == 0){
			if(verifyAllPostVars(["testo", "ID_sezione", "risultato", "punteggio"])) {
				$ID_sezione = $_POST["ID_sezione"];
				$sezione = new Sezione($ID_sezione, ["ID_verifica"]);
				$ordine = Sezione::getUltimoOrdineSezione($ID_sezione) + 1;
				$ID_verifica = $sezione->ID_verifica;
				
				if(Verofalso::create([
					"testo" => $_POST["testo"],
					"ID_sezione" => $ID_sezione,
					"ID_verifica" => $ID_verifica,
					"risultato" => ($_POST["risultato"] == "1" ? "1" : "0"),
					"punteggio" => $_POST["punteggio"],
					"note" => $_POST["note"] ?? "",
					"ordine" => $ordine]
				)){
					$message->setMessageType(MessageType::Success)
						->setMessage("Vero-Falso creato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio del Vero-Falso")
						->show();
				}
			}
		}else{
			if(verifyAllPostVars(["testo", "risultato", "punteggio"])) {
				if(Verofalso::edit(
					$_POST["id"],
					[
						"testo" => $_POST["testo"],
						"risultato" => $_POST["risultato"],
						"punteggio" => $_POST["punteggio"],
						"note" => $_POST["note"] ?? ""
					])) {
					$message->setMessageType(MessageType::Success)
						->setMessage("Vero-Falso modificato correttamente")
						->show();
				}else{
					$message->setMessageType(MessageType::Error)
						->setMessage("Errore nel salvataggio del Vero-Falso")
						->show();
				}
			}
		}
	}
	function eliminaVerofalso(): void {
		global $message;
		
		if(Verofalso::delete($_POST["id"])) {
			$message->setMessageType(MessageType::Success)
				->setMessage("Vero-Falso eliminato correttamente")
				->show();
		}else{
			$message->setMessageType(MessageType::Error)
				->setMessage("Errore nell'eliminazione del Vero-Falso")
				->show();
		}
	}
	
	
	$typeToFunction = array(
		"modifica-sezione" => "modificaSezione",
		"elimina-sezione" => "eliminaSezione",
		"modifica-verofalso" => "modificaVerofalso",
		"elimina-verofalso" => "eliminaVerofalso",
	);
	
	if(verifyAllPostVars(["id", "type"])){
		call_user_func($typeToFunction[$_POST["type"]]);
	}