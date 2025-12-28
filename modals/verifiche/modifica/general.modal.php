<div class="modal fade" id="modal-general" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
	<form class="needs-validation" novalidate action="" method="post" id="form-general">
		<input type="hidden" name="ID_sezione">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal-general-titolo">Selezione tipologia esercizio</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							Seleziona la tipologia di esercizio da creare
							<hr>
							<label for="tipologia" class="form-label">Tipologia esercizio</label>
							<select class="form-select" id="tipologia" name="tipologia" required>
								<option selected disabled value="">Scegli...</option>
								<option value="verofalso">Vero-Falso</option>
								<option value="rispostamultipla">Risposta Multipla</option>
								<option value="rispostaaperta">Risposta Aperta</option>
								<option value="esercizio">Esercizio</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-primary">Aggiungi</button>
				</div>
			</div>
		</div>
	</form>
</div>