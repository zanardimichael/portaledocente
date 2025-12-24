<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Professore.php";
	
	$id = null;
	if (verifyAllGetVars(["id"])){
		$id = $_GET["id"];
	}else{
		echo "<script> location.href = \"/pages/classi\"</script>";
		exit();
	}
	$materia = new Materia($id);

?>

<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Materia</div></div>
			
			<div class="card-body">
				<div class="mb-2">
					<h5>Nome</h5>
					<?php echo $materia->nome; ?>
				</div>
				<div class="mb-2">
					<h5>Numero Alunni</h5>
					<?php
						$classi = $materia->getClassi(true);
						
						$num_alunni = 0;
						foreach($classi as $classe){
							$num_alunni += $classe->getNumeroAlunni();
						}
						echo $num_alunni;
					?>
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
			<div class="card-header"><div class="card-title">Classi e Professori</div></div>
			<div class="card-body">
				<table class="table table-striped" id="materia-professore-classe-table" style="max-height: 1000px;">
					<thead>
					<tr><th>Nome</th><th>Note classe</th><th>Professori</th></tr>
					</thead>
					<tbody>
					<?php
						$classi_professori = $materia->getClassiProfessori();
						foreach($classi_professori as $id_classe => $professori){
							$classe = new Classe($id_classe, ["classe", "sezione", "note"]);
							$professori_array_txt = [];
							$professori_txt = "";
							foreach ($professori as $id_professore){
								$professore = new Professore($id_professore);
								$professori_array_txt[] = $professore->utente->getNomeCognome();
							}
							$professori_txt = implode(", ", $professori_array_txt);
							?>
							<tr>
								<td><?php echo $classe->getNomeClasse(); ?></td>
								<td><?php echo $classe->note; ?></td>
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