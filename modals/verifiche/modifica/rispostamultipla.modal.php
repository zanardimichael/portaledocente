<div class="modal fade" id="modal-rispostamultipla" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-rispostamultipla">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="modifica-rispostamultipla">
		<input type="hidden" name="ID_sezione">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-rispostamultipla-titolo">Modifica Risposta multipla</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 mb-2">
							<label for="titolo" class="form-label">Testo</label>
							<input
								type="text"
								class="form-control"
								id="testo"
								name="testo"
								value=""
								maxlength="512"
								required
							/>
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

<div class="modal fade" id="modal-risposta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-risposta">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="modifica-risposta">
		<input type="hidden" name="ID_rispostamultipla">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-risposta-titolo">Modifica Risposta</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 mb-2">
							<label for="testo" class="form-label">Testo</label>
							<input
								type="text"
								class="form-control"
								id="testo"
								name="testo"
								value=""
								maxlength="512"
								required
							/>
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
								min="0"
								max="64"
								required
							/>
							<div class="valid-feedback">Va bene!</div>
						</div>
						<div class="col-md-12">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="1" id="corretto" name="corretto">
								<label class="form-check-label" for="corretto">
									Corretta
								</label>
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


<div class="modal fade" id="modal-elimina-rispostamultipla" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-elimina-rispostamultipla">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="elimina-rispostamultipla">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-elimina-rispostamultipla-titolo">Elimina Risposta multipla</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Sei sicuro di voler eliminare la Domanda a risposta multipla?<br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-danger">Elimina</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modal-elimina-risposta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-elimina-risposta">
		<input type="hidden" name="id">
		<input type="hidden" name="type" value="elimina-risposta">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-elimina-risposta-titolo">Elimina Risposta</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Sei sicuro di voler eliminare la risposta?<br/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-danger">Elimina</button>
				</div>
			</div>
		</div>
	</form>
</div>