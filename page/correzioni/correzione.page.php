<?php
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/CorrezioneDomanda.php';
?>


<div class="card">
	<div class="card-header">
		Sezione 1
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-12 mb-3">
				<?php
					$correzione_domanda = new CorrezioneDomanda(1);
				
					echo $correzione_domanda->render();
				?>
				<div class="card card-success card-outline">
					<div class="card-header">
						Risposta Multipla 1
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-6 mb-2">
								<p>Quale livello OSI corrisponde al livello di “Internet” del modello TCP/IP?</p>
								<label class="form-label">Risposta</label>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="checkDefault">
									<label class="form-check-label" for="checkDefault">
										Physical
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="checkDefault1">
									<label class="form-check-label" for="checkDefault1">
										Data Link
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="checkDefault2">
									<label class="form-check-label" for="checkDefault2">
										Network <b>Corretta</b>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="checkDefault3">
									<label class="form-check-label" for="checkDefault3">
										Application
									</label>
								</div>
							</div>
							<div class="col-sm-12 col-md-6 mb-2">
								<label class="form-label">Parziale</label>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="checkDefault">
									<label class="form-check-label" for="checkDefault">
										Specifica punteggio parziale
									</label>
								</div>
								<hr>
								<label class="form-label" for="punteggio">Punteggio Parziale</label>
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
							</div>
						</div>
					</div>
					<div class="card-footer">
						Punteggio: 0 punti
					</div>
				</div>
			</div>
		</div>
	</div>
</div>