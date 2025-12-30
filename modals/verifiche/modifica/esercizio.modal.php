<div class="modal fade" id="modal-esercizio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-esercizio">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="modifica-esercizio">
		<input type="hidden" name="ID_sezione">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-esercizio-titolo">Modifica Esercizio</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 mb-2">
							<label for="titolo" class="form-label">Titolo</label>
							<input
								type="text"
								class="form-control"
								id="titolo"
								name="titolo"
								value=""
								maxlength="512"
								required
							/>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12 mb-2">
							<label for="testo" class="form-label">Testo</label>
							<textarea id="testo" class="form-control" name="testo" aria-label="Note" required></textarea>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12 mb-2">
							<label for="punteggio" class="form-label">Punteggio</label>
							<input
								type="number"
								class="form-control"
								id="punteggio"
								name="punteggio"
								value=""
								min="1"
								max="64"
								required
							/>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-text">Note</span>
								<textarea class="form-control" name="note" aria-label="Note" maxlength="64"></textarea>
							</div>
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

<div class="modal fade" id="modal-elimina-esercizio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-elimina-esercizio">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="elimina-esercizio">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-elimina-esercizio-titolo">Elimina Risposta Aperta</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Sei sicuro di voler eliminare la Domanda a risposta aperta?<br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-danger">Elimina</button>
				</div>
			</div>
		</div>
	</form>
</div>