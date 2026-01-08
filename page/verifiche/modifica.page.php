<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Sezione.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verofalso.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Rispostamultipla.php";
	
	/** @var Message $message */
	global $message;
	global $current_prof;
	global $page;
	
	$id = null;
	if(verifyAllGetVars(["id"])){
		$id = $_GET["id"];
	}else{
		$page->setRedirect("/pages/verifiche")
			->setUnsafe(UnsafeReasons::Redirect);
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		include "modifica/postHandler.php";
	}
	
	if(verifyAllGetVars(["createSuccess"])){
		$page->message->setMessage("Verifica creata correttamente")
			->setMessageType(MessageType::Success)
			->show();
	}
	
	if($page->isSafeToProceed()){
		$verifica = new Verifica($id);
		$punteggio_generale = 0;
	
	
		if($verifica->professore->id != $current_prof->id){
			$page->setRedirect("/pages/verifiche")
				->setUnsafe(UnsafeReasons::Unauthorized);
		}
	
		if($page->isSafeToProceed()){
            $page->addJavascriptVariable("ID_verifica", $verifica->id);
	
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="card card-info card-outline mb-4">
				<form class="needs-validation" novalidate action="" method="post" id="form-verifica">
					<div class="card-header">
						<div class="card-title">
							Modifica Verifica <b><?php echo $verifica->titolo; ?></b>
						</div>
						<button class="btn btn-info float-end btn-sm" type="submit">Salva Verifica</button>
					</div>
					<input type="hidden" name="id" value="<?php echo $verifica->id; ?>">
					<input type="hidden" name="type" value="salva-verifica">
					<div class="card-body">
						<div class="row g-3">
							<div class="col-md-12">
								<label for="titolo" class="form-label">Titolo</label>
								<input
										type="text"
										class="form-control"
										id="titolo"
										name="titolo"
										value="<?php echo $verifica->titolo; ?>"
										maxlength="128"
										required
								/>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<label for="classe" class="form-label">Classe</label>
								<select class="form-select" id="classe" name="classe" required>
									<option disabled value="">Scegli...</option>
									<?php
										$classi = Classe::getAll(true, ["classe", "sezione"]);
										
										foreach ($classi as $classe) {
											if($classe->id == $verifica->ID_classe) $selected = "selected"; else $selected = "";
											echo "<option value=\"$classe->id\" $selected>" . $classe->getNomeClasse() . "</option>";
										}
									?>
								</select>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<label for="materia" class="form-label">Materia</label>
								<select class="form-select" id="materia" name="materia" required>
									<option disabled value="">Scegli...</option>
									<?php
										$materie = Materia::getMaterieClasseProfessore($verifica->ID_classe, $current_prof->id);
										
										foreach ($materie as $materia) {
											if($materia->id == $verifica->ID_materia) $selected = "selected"; else $selected = "";
											echo "<option value=\"$materia->id\" $selected>" . $materia->nome . "</option>";
										}
									?>
								</select>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-text">Note</span>
									<textarea class="form-control" name="note" aria-label="Note"><?php echo $verifica->note; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-12 mb-2">
			<h3 class="float-start">Struttura Verifica</h3>
			<button type="button" class="btn btn-primary float-end aggiungi-sezione">Nuova sezione</button>
		</div>
		<?php
			$sezioni = $verifica->getSezioni();
			$page->addJavascriptVariable("ordine_sezione_max",  count($sezioni));
			for($i = 0; $i < count($sezioni); $i++) {
				$sezione = $sezioni[$i];
				?>
				<div class="col-12 sezione" id-sezione="<?php echo $sezione->id; ?>" ordine="<?php echo $sezione->ordine; ?>">
					<div class="card card-info card-outline mb-4">
						<div class="card-header">
							<div class="card-title">
								<?php echo $sezione->titolo; ?>
							</div>
							<div class="float-end">
								<div class="btn-group btn-group-sm">
									<button type="button" class="btn btn-sm btn-primary modifica-sezione" id-sezione="<?php echo $sezione->id; ?>">Modifica sezione</button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-giu-sezione" id-sezione="<?php echo $sezione->id; ?>" <?php if($i == count($sezioni)-1) echo "disabled"; ?>><i class="bi bi-chevron-down"></i></button>
									<button type="button" class="btn btn-sm btn-outline-primary ordina-su-sezione" id-sezione="<?php echo $sezione->id; ?>" <?php if($i == 0) echo "disabled"; ?>><i class="bi bi-chevron-up"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row g-3 esercizi">
								<div class="col-12"><div class="float-end"><button type="button" class="btn btn-sm btn-primary aggiungi-esercizio" id-sezione="<?php echo $sezione->id; ?>">Aggiungi esercizio</button></div></div>
								<?php
									$domande_array = $sezione->getDomande();
									$domande = $domande_array["domande"];
									$ordine_max = count($domande);
									for($j = 1; $j <= count($domande); $j++) {
										echo $domande[$j]->render($j, $ordine_max);
									}
									$punteggio_generale += $domande_array["punteggio"];
								?>
                                <div class="col-12"><div class="float-end"><button type="button" class="btn btn-sm btn-primary aggiungi-esercizio" id-sezione="<?php echo $sezione->id; ?>">Aggiungi esercizio</button></div></div>
							</div>
						</div>
						<div class="card-footer">
							<button type="button" class="btn btn-sm btn-danger elimina-sezione" id-sezione="<?php echo $sezione->id; ?>">Elimina sezione</button>
							<div class="float-end">Punteggio massimo: <?php echo $domande_array["punteggio"]; ?> punt<?php echo $domande_array["punteggio"] == "1"? "o" : "i"?></div>
						</div>
					</div>
				</div>
				<?php
			}
		
		?>
		<div class="col-12">
			<div class="float-end">
				Punteggio verifica: <?php echo $punteggio_generale; ?> punt<?php echo $punteggio_generale == "1"? "o" : "i"?>
			</div>
		</div>
	</div>
</div>
<?php
		}
	}
?>