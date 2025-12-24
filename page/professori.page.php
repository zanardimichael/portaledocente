<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Professore.php";
	
	$professori = Professore::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="professori-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Nome</th><th>Classi</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($professori as $professore){
				$classi = $professore->getClassi(true);
				$classi_classe_array = [];
				foreach($classi as $classe){
					$classi_classe_array[] = $classe->getNomeClasse();
				}
				$classi_text = implode(", ", $classi_classe_array);
				?>
				<tr>
					<td><?php echo $professore->utente->getNomeCognome(); ?></td>
					<td><?php echo $classi_text ?></td>
					<td>
						<a class="btn btn-success" href="/pages/professori/visualizza?id=<?php echo $professore->id; ?>"><i class="bi bi-search"></i></a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>