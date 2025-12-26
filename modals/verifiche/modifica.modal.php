<div class="modal fade" id="modal-sezione" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-sezione">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="modifica-sezione">
		<input type="hidden" name="ID_verifica">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-sezione-titolo">Modifica Sezione</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label for="titolo" class="form-label">Titolo</label>
							<input
								type="text"
								class="form-control"
								id="titolo"
								name="titolo"
								value=""
								maxlength="128"
								required
							/>
							<div class="valid-feedback">Va bene!</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-primary">Salva</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modal-elimina-sezione" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-elimina-sezione">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="elimina-sezione">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-sezione-titolo">Elimina Sezione</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Sei sicuro di voler eliminare la sezione?<br/>
					Tutti gli esercizi e le domande al suo interno verranno eliminati.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-danger">Elimina</button>
				</div>
			</div>
		</div>
	</form>
</div>