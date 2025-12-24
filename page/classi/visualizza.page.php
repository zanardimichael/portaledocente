<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Professore.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Materia.php";
	
	$id = null;
	if (verifyAllGetVars(["id"])){
		$id = $_GET["id"];
	}else{
		echo "<script> location.href = \"/pages/classi\"</script>";
		exit();
	}
	$classe = new Classe($id);

?>

<div class="row" data-masonry='{"percentPosition": true }'>
	<div class="col-md-6">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Classe <?php echo $classe->classe.$classe->sezione; ?></div></div>
			
			<div class="card-body">
				<div class="mb-2">
					<h5>Classe</h5>
					<?php echo $classe->classe; ?>
				</div>
				<div class="mb-2">
					<h5>Sezione</h5>
					<?php echo $classe->sezione; ?>
				</div>
				<div class="mb-2">
					<h5>Anno Scolastico</h5>
					<?php echo $classe->getAnnoScolastico(); ?>
				</div>
				<div class="mb-2">
					<h5>Numero Alunni</h5>
					<?php echo $classe->getNumeroAlunni(); ?>
				</div>
			</div>
			<!--end::JavaScript-->
		</div>
		<!--end::Form Validation-->
	</div>
	<div class="col-md-6">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Alunni</div></div>
			<div class="card-body">
				<table class="table table-striped" id="alunni-table" style="max-height: 1000px;">
					<thead>
					<tr><th>Nome</th><th>Note</th></tr>
					</thead>
					<tbody>
					<?php
						$alunni = $classe->getAlunni(true);
						foreach($alunni as $alunno){
							?>
							<tr>
								<td><?php echo $alunno->getNomeCognome(); ?></td>
								<td><?php echo $alunno->note; ?></td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Materie e Professori</div></div>
			<div class="card-body">
				<table class="table table-striped" id="materia-professore-classe-table" style="max-height: 1000px;">
					<thead>
					<tr><th>Materia</th><th>Note materia</th><th>Professori</th></tr>
					</thead>
					<tbody>
					<?php
						$professori_materie = $classe->getProfessoriMaterie();
						foreach($professori_materie as $id_materia => $professori){
							$materia = new Materia($id_materia, ["nome", "note"]);
							$professori_array_txt = [];
							$professori_txt = "";
							foreach ($professori as $id_professore){
								$professore = new Professore($id_professore);
								$professori_array_txt[] = $professore->utente->getNomeCognome();
							}
							$professori_txt = implode(", ", $professori_array_txt);
							?>
							<tr>
								<td><?php echo $materia->nome; ?></td>
								<td><?php echo $materia->note; ?></td>
								<td><?php echo $professori_txt ?></td>
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