<?php
	
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	
	global $page;
	
	if(verifyAllGetVars(["deleteSuccess"])){
		$page->message->setMessage("Verifica eliminata correttamente")
			->setMessageType(MessageType::Success)
			->show();
	}
	
	$verifiche = Verifica::getAll(true);
?>


<div class="row">
	<table class="table table-striped" id="verifiche-table" style="max-height: 1000px;">
		<thead>
		<tr><th></th><th>ID</th><th>Titolo</th><th>Classe</th><th>Materia</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($verifiche as $verifica){
				if($verifica->ID_verifica != null) continue;
				?>
				<tr>
					<td></td>
					<td><?php echo $verifica->id; ?></td>
					<td><?php echo $verifica->titolo; ?></td>
					<td><?php echo $verifica->classe->getNomeClasse(); ?></td>
					<td><?php echo $verifica->materia->nome; ?></td>
					<td>
						<!--<a class="btn btn-success" href="/pages/verifiche/visualizza?id=<?php echo $verifica->id; ?>"><i class="bi bi-search"></i></a>-->
						<a class="btn btn-primary" href="/pages/verifiche/modifica?id=<?php echo $verifica->id; ?>"><i class="bi bi-pencil-square"></i></a>
						<a class="btn btn-danger" href="/pages/verifiche/elimina?id=<?php echo $verifica->id; ?>"><i class="bi bi-trash"></i></a>
						<a class="btn btn-success" href="/download.php?type=verifica_latex&id=<?php echo $verifica->id; ?>"><i class="bi bi-download"></i></a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>