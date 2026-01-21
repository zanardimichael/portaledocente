<?php
	global $current_prof;
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Correzione.php";
	
	$correzioni = Correzione::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="correzioni-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Verifica</th><th>Data Verifica</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($correzioni as $correzione){
				if($correzione->verifica->ID_professore != $current_prof->id) continue;
				?>
				<tr>
					<td><?php echo $correzione->verifica->titolo; ?></td>
					<td><?php echo $correzione->getDataVerifica(); ?></td>
					<td>
						<a class="btn btn-success" href="/pages/correzioni/visualizza?id=<?php echo $correzione->id; ?>" disabled><i class="bi bi-search"></i></a>
						<a class="btn btn-primary" href="/pages/correzioni/alunni?id=<?php echo $correzione->id; ?>"><i class="bi bi-pencil-square"></i></a>
						<a class="btn btn-danger" href="/pages/correzioni/elimina?id=<?php echo $correzione->id; ?>"><i class="bi bi-trash"></i></a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>