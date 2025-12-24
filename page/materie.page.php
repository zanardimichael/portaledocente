<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Materia.php";
	
	$materie = Materia::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="materie-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Nome</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($materie as $materia){
				?>
				<tr>
					<td><?php echo $materia->nome; ?></td>
					<td>
						<a class="btn btn-success" href="/pages/materie/visualizza?id=<?php echo $materia->id; ?>" disabled><i class="bi bi-search"></i></a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>