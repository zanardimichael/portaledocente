<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
	$id = null;
	if (verifyAllGetVars(["id"])){
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
			<div class="card-header"><div class="card-title">Alunno <b><?php echo $alunno->getNomeCognome(); ?></b></div></div>
			
			<div class="card-body">
				<div class="mb-2">
					<h5>Nome</h5>
					<?php echo $alunno->nome; ?>
				</div>
				<div class="mb-2">
					<h5>Cognome</h5>
					<?php echo $alunno->cognome; ?>
				</div>
				<div class="mb-2">
					<h5>Classe</h5>
					<?php echo $alunno->classe->getNomeClasse(); ?>
				</div>
				<div class="mb-2">
					<h5>Note aggiuntive</h5>
					<?php echo $alunno->note != "" ? $alunno->note : "-"; ?>
				</div>
			</div>
			<!--end::JavaScript-->
		</div>
		<!--end::Form Validation-->
	</div>
</div>