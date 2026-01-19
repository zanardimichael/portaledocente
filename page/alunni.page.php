<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Alunno.php";
	
	$alunni = Alunno::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="alunni-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Classe</th><th>Nome</th><th>Cognome</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($alunni as $alunno){
				?>
				<tr>
					<td><?php echo $alunno->classe->getNomeClasse(); ?></td>
					<td><?php echo $alunno->nome; ?></td>
					<td><?php echo $alunno->cognome; ?></td>
					<td>
						<a class="btn btn-success" href="/pages/alunni/visualizza?id=<?php echo $alunno->id; ?>"><i class="bi bi-search"></i></a>
						<a class="btn btn-primary" href="/pages/alunni/modifica?id=<?php echo $alunno->id; ?>"><i class="bi bi-pencil-square"></i></a>
						<a class="btn btn-danger" href="/pages/alunni/elimina?id=<?php echo $alunno->id; ?>"><i class="bi bi-trash"></i></a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>