<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	
	$classi = Classe::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="classi-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Classe</th><th>Anno Scolastico</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($classi as $classe){
				?>
				<tr>
					<td><?php echo $classe->classe.$classe->sezione; ?></td>
					<td><?php echo $classe->anno."/".$classe->anno+1; ?></td>
					<td>
						<button class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
						<button class="btn btn-danger"><i class="bi bi-trash"></i></button>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>