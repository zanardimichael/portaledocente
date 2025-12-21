<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
	$id = null;
	if (verifyAllGetVars(["id"])){
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
</div>
<div class="row">
	<div class="col-12">
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
						foreach($alunni as $classe){
							?>
							<tr>
								<td><?php echo $classe->getNomeCognome(); ?></td>
								<td><?php echo $classe->note; ?></td>
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