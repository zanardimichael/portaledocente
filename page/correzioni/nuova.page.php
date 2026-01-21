<?php
	global $page;
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/Correzione.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/Verifica.php';
	
	if($page->getRequestMethod() == 'POST') {
		if(verifyAllPostVars(["ID_verifica"])){
			$id = Correzione::create([
				"ID_verifica" => $_POST["ID_verifica"],
				"data_verifica" => $_POST["data_verifica"],
				"note" => $_POST["note"] ?? "",
			]);
			if($id){
				$page->setRedirect("/pages/correzioni/alunni?id=$id&createSuccess")->setUnsafe(UnsafeReasons::Redirect);
			}else{
				$page->message->setMessage("Errore durante la creazione della correzione")->setMessageType(MessageType::Error)->show();
			}
		}
	}
	
	if($page->isSafeToProceed()) {
		$verifiche = Verifica::getAll(true);
?>


<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Nuova Correzione</div></div>
			<form class="needs-validation" novalidate action="" method="post" id="form-nuova-correzione">
				<input type="hidden" id="ID_verifica" name="ID_verifica">
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-12">
							<label for="data_verifica" class="form-label">Data Verifica</label>
							<input
								type="date"
								class="form-control"
								id="data_verifica"
								name="data_verifica"
								required
							/>
							<div class="invalid-feedback">Inserisci la data della verifica</div>
						</div>
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-text">Note</span>
								<textarea class="form-control" name="note" aria-label="Note"></textarea>
							</div>
						</div>

					</div>
				</div>
				<div class="card-footer">
					<button class="btn btn-info" type="submit">Aggiungi</button>
				</div>
			</form>
		</div>
	</div>
	<h5>Seleziona verifica da correggere</h5>
	<table class="table table-striped" id="verifiche-table" style="max-height: 1000px;">
		<thead>
		<tr><th></th><th>Titolo</th><th>Classe</th><th>Materia</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($verifiche as $verifica){
				?>
				<tr>
					<td><?php echo $verifica->id; ?></td>
					<td><?php echo $verifica->titolo; ?></td>
					<td><?php echo $verifica->classe->getNomeClasse(); ?></td>
					<td><?php echo $verifica->materia->nome; ?></td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>

<?php
	}
?>