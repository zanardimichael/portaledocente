<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Sezione.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verofalso.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Rispostamultipla.php";
	
	global $current_prof;
	global $page;
	
	$ID_verifica = $_GET["ID_verifica"] ?? "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" and verifyAllPostVars(["titolo", "classe", "materia", "note"])){
		
		$ID_classe = $_POST["classe"];
		$ID_materia = $_POST["materia"];
		
		if($_POST["ID_verifica"] != "" && Verifica::exists($_POST["ID_verifica"])){
			$verifica = new Verifica($_POST["ID_verifica"]);
			
			$ID_classe = $verifica->ID_classe;
			$ID_materia = $verifica->ID_materia;
		}
		
		$data = array(
			"titolo" => $_POST["titolo"],
			"ID_classe" => $ID_classe,
			"ID_materia" => $ID_materia,
			"ID_professore" => $current_prof->id,
			"ID_verifica" => $_POST["ID_verifica"] ?? null,
			"ordine" => 1,
			"note" => $_POST["note"] ?? ""
		);
		
		$id = Verifica::create($data);
		if($id){
			$page->setRedirect("/pages/verifiche/modifica?id=".$id."&createSuccess")
				->setUnsafe(UnsafeReasons::Redirect);
		}else{
			$page->message->setMessageType(MessageType::Error)
				->setMessage("Errore nella creazione della verifica")
				->show();;
		}
	}
	
	$verifica = null;
	if($ID_verifica != ""){
		if(Verifica::exists($ID_verifica)){
			$verifica = new Verifica($ID_verifica);
		}else{
			$page->setRedirect("/pages/verifiche")
				->setUnsafe(UnsafeReasons::NotFound);
		}
	}
	
	if($page->isSafeToProceed()){
	
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="card card-info card-outline mb-4">
				<form class="needs-validation" novalidate action="" method="post" id="form-verifica">
					<div class="card-header">
						<div class="card-title">
							<?php echo $verifica !== null ? "Nuova sotto-verifica - <b>".$verifica->titolo."</b>" : "Nuova Verifica"; ?>
						</div>
					</div>
					<input type="hidden" name="id">
					<input type="hidden" name="ID_verifica" value="<?php echo $ID_verifica; ?>">
					<div class="card-body">
						<div class="row g-3">
							<div class="col-md-12">
								<label for="titolo" class="form-label">Titolo</label>
								<input
										type="text"
										class="form-control"
										id="titolo"
										name="titolo"
										value=""
										maxlength="128"
										required
								/>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<label for="classe" class="form-label">Classe</label>
								<select class="form-select" id="classe" name="classe" required <?php echo $verifica !== null ? "readonly": ""; ?>>
									<option selected disabled value="">Scegli...</option>
									<?php
										$classi = Classe::getAll(true, ["classe", "sezione"]);
										
										foreach ($classi as $classe) {
											$selected = "";
											if($verifica !== null and $classe->id == $verifica->ID_classe){
												$selected = "selected";
											}
											echo "<option value=\"$classe->id\" $selected>" . $classe->getNomeClasse() . "</option>";
										}
									?>
								</select>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<label for="materia" class="form-label">Materia</label>
								<select class="form-select" id="materia" name="materia" required <?php echo $verifica !== null ? "readonly": ""; ?>>
									<option selected disabled value="">Scegli...</option>
									<?php
										if($verifica !== null) {
											$materia = new Materia($verifica->ID_materia);
											
											echo "<option selected value=\"$materia->id\">$materia->nome</option>";
										}
									?>
								</select>
								<div class="valid-feedback">Va bene!</div>
							</div>
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-text">Note</span>
									<textarea class="form-control" name="note" aria-label="Note"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button class="btn btn-info" type="submit">Aggiungi</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>