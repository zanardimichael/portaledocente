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
	$professore = new Professore($id);

?>

<div class="row" data-masonry='{"percentPosition": true }'>
	<div class="col-md-6">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Professore</div></div>
			
			<div class="card-body">
				<div class="mb-2">
					<h5>Nome</h5>
					<?php echo $professore->utente->getNomeCognome(); ?>
				</div>
				<div class="mb-2">
					<h5>Numero Alunni</h5>
					<?php
						$classi = $professore->getClassi(true);
						
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
						foreach ($classi as $classe){
							$alunni = $classe->getAlunni(true);
							foreach($alunni as $alunno){
								?>
								<tr>
									<td><?php echo $alunno->getNomeCognome(); ?></td>
									<td><?php echo $alunno->note; ?></td>
								</tr>
								<?php
							}
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Classi e Materie</div></div>
			<div class="card-body">
				<table class="table table-striped" id="materia-professore-classe-table" style="max-height: 1000px;">
					<thead>
					<tr><th>Nome</th><th>Note classe</th><th>Materie</th></tr>
					</thead>
					<tbody>
					<?php
						$classi_materie = $professore->getClassiMaterie();
						foreach($classi_materie as $id_classe => $materie){
							$classe = new Classe($id_classe, ["classe", "sezione", "note"]);
							$materia_array_txt = [];
							$materia_txt = "";
							foreach ($materie as $id_materia){
								$materia = new Materia($id_materia);
								$materia_array_txt[] = $materia->nome;
							}
							$materia_txt = implode(", ", $materia_array_txt);
							?>
							<tr>
								<td><?php echo $classe->getNomeClasse(); ?></td>
								<td><?php echo $classe->note; ?></td>
								<td><?php echo $materia_txt ?></td>
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