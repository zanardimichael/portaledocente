<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
	$id = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST' and verifyAllPostVars(["classe", "nome", "cognome", "email"])) {
		$data = array(
			"ID_classe" => $_POST["classe"],
			"numero_registro" => $_POST["numero_registro"],
			"nome" => $_POST["nome"],
			"cognome" => $_POST["cognome"],
			"email" => $_POST["email"]
		);
		if (isset($_POST["note"])) {
			$data["note"] = $_POST["note"];
		}
		if (Alunno::edit($_POST["id"], $data)) {
			echo "<script> location.href = \"/pages/alunni?updateSuccess\"</script>";
			exit();
		} else {
			echo '<script>
						document.addEventListener("DOMContentLoaded", () => {
							Toastify({
								text: "Errore creazione",
								duration: 3000,
								style: {"background": "red"}
							}).showToast();
						})    
					</script>';
		}
	} elseif (verifyAllGetVars(["id"])){
		$id = $_GET["id"];
	}else{
		echo "<script> location.href = \"/pages/alunni\"</script>";
		exit();
	}
	$alunno = new Alunno($id);

?>

<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header">
				<div class="card-title">Modifica Alunno <b><?php echo $alunno->getNomeCognome(); ?></b></div>
			</div>
			<form class="needs-validation" novalidate action="" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<div class="card-body">
					<div class="row g-3">
						<div class="col-12">
							<label class="form-label" for="numero_registro">Numero di Registro</label>
							<input
								type="number"
								class="form-control"
								id="numero_registro"
								name="numero_registro"
								value="<?php echo $alunno->numero_registro; ?>"
								min="1"
								step="1"
								max="40"
								required
							/>
						</div>
						<div class="col-md-6">
							<label for="nome" class="form-label">Nome</label>
							<input
									type="text"
									class="form-control"
									id="nome"
									name="nome"
									maxlength="64"
									value="<?php echo $alunno->nome; ?>"
									required
							/>
							<div class="invalid-feedback">Inserisci il nome</div>
						</div>
						<div class="col-md-6">
							<label for="cognome" class="form-label">Cognome</label>
							<input
									type="text"
									class="form-control"
									id="cognome"
									value="<?php echo $alunno->cognome; ?>"
									name="cognome"
									maxlength="64"
									required
							/>
							<div class="invalid-feedback">Inserisci il cognome</div>
						</div>
						<div class="col-md-12">
							<label for="email" class="form-label">Email</label>
							<div class="input-group mb-3">
								<input type="text" name="email" id="email" class="form-control" placeholder="Email"
									   value="<?php echo str_replace("@issgreppi.it", "", $alunno->email); ?>"
									   aria-label="Email" aria-describedby="email-append">
								<span class="input-group-text" id="email-append">@issgreppi.it</span>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="emailAutogenerata">
								<label class="form-check-label" for="emailAutogenerata">
									Generata automaticamente
								</label>
							</div>
						</div>
						<div class="col-md-12">
							<label for="classe" class="form-label">Classe</label>
							<select class="form-select" id="classe" name="classe" required>
								<option selected disabled value="">Scegli...</option>
								<?php
								$classi = Classe::getAll(true, ["classe", "sezione"]);

								foreach ($classi as $classe) {
									if($classe->id == $alunno->ID_classe) $selected = "selected"; else $selected = "";
									echo "<option value=\"$classe->id\" $selected>" . $classe->getNomeClasse() . "</option>";
								}
								?>
							</select>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-text">Note</span>
								<textarea class="form-control" name="note" aria-label="Note"><?php echo $alunno->note; ?></textarea>
							</div>
						</div>

					</div>
				</div>
				<div class="card-footer">
					<button class="btn btn-info" type="submit">Salva</button>
				</div>
			</form>
			<script>
				// Example starter JavaScript for disabling form submissions if there are invalid fields
				(() => {
					'use strict';

					// Fetch all the forms we want to apply custom Bootstrap validation styles to
					const forms = document.querySelectorAll('.needs-validation');

					// Loop over them and prevent submission
					Array.from(forms).forEach((form) => {
						form.addEventListener(
							'submit',
							(event) => {
								if (!form.checkValidity()) {
									event.preventDefault();
									event.stopPropagation();
								}

								form.classList.add('was-validated');
							},
							false,
						);
					});
				})();
			</script>
			<!--end::JavaScript-->
		</div>
		<!--end::Form Validation-->
	</div>
</div>