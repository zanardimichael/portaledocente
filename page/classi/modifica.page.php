<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
	$id = null;
	if($_SERVER['REQUEST_METHOD'] == 'POST' and verifyAllPostVars(["id", "anno", "classe", "sezione"])){
		$data = array(
			"anno" => $_POST["anno"],
			"classe" => $_POST["classe"],
			"sezione" => $_POST["sezione"]
		);
		if(isset($_POST["note"])){
			$data["note"] = $_POST["note"];
		}
		if(Classe::edit($_POST["id"], $data)){
			echo "<script> location.href = \"/pages/classi?updateSuccess\"</script>";
		}else{
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
		echo "<script> location.href = \"/pages/classi\"</script>";
		exit();
	}
	$classe = new Classe($id);

?>

<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Modifica Classe <?php echo $classe->classe.$classe->sezione; ?></div></div>
			<form class="needs-validation" novalidate action="" method="post">
				<input type="hidden" name="id" value="<?php echo $classe->id; ?>">
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-12">
							<label for="anno" class="form-label">Anno Scolastico</label>
							<select class="form-select" id="anno" name="anno" required>
								<option selected disabled value="">Scegli...</option>
								<option value="2025" <?php echo $classe->anno == "2025" ? "selected": "" ?>>2025/2026</option>
								<option value="2026" <?php echo $classe->anno == "2026" ? "selected": "" ?>>2026/2027</option>
							</select>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12">
							<label for="classe" class="form-label">Classe</label>
							<select class="form-select" id="classe" name="classe" required>
								<option selected disabled value="">Scegli...</option>
								<option value="1" <?php echo $classe->classe == "1" ? "selected": "" ?>>1</option>
								<option value="2" <?php echo $classe->classe == "2" ? "selected": "" ?>>2</option>
								<option value="3" <?php echo $classe->classe == "3" ? "selected": "" ?>>3</option>
								<option value="4" <?php echo $classe->classe == "4" ? "selected": "" ?>>4</option>
								<option value="5" <?php echo $classe->classe == "5" ? "selected": "" ?>>5</option>
							</select>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12">
							<label for="sezione" class="form-label">Sezione</label>
							<input
								type="text"
								class="form-control"
								id="sezione"
								name="sezione"
								value="<?php echo $classe->sezione ?>"
								maxlength="4"
								required
							/>
							<div class="invalid-feedback">Inserisci la sezione</div>
						</div>
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-text">Note</span>
								<textarea class="form-control" name="note" aria-label="Note"><?php echo $classe->note; ?></textarea>
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
<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Alunni</div></div>
			<div class="card-body">
				<table class="table table-striped" id="alunni-table" style="max-height: 1000px;">
					<thead>
						<tr><th>Classe</th><th>Nome</th></tr>
					</thead>
					<tbody>
					<?php
						$alunni = $classe->getAlunni(true);
						foreach($alunni as $classe){
							?>
							<tr>
								<td><?php echo $classe->classe->getNomeClasse(); ?></td>
								<td><?php echo $classe->getNomeCognome(); ?></td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>