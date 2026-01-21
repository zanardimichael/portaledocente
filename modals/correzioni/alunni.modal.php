<?php
	global $page;
?>
<div class="modal fade" id="modal-alunni" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-alunni">
		<input type="hidden" name="id">
		<input type="hidden" name="alunni">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-alunni-titolo">Aggiunta Alunno</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<table class="table table-striped" id="aggiunta-alunni-table" style="max-height: 1000px;">
							<thead>
							<tr><th>ID</th><th>Nome</th></tr>
							</thead>
							<tbody>
								<?php
									$correzione = new Correzione($page->getGlobalVariable("ID_correzione"));
									$alunni = $correzione->getAlunniNonSelezionati();
									
									foreach ($alunni as $alunno) {
										?>
										<tr>
											<td><?php echo $alunno->id; ?></td>
											<td><?php echo $alunno->getNomeCognome(); ?></td>
										</tr>
										<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="button" class="btn btn-primary aggiungi-alunni">Salva</button>
				</div>
			</div>
		</div>
	</form>
</div>