<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	global $page;
	$verifiche = Verifica::getAll(true);
?>
<div class="row mb-4">
	<div class="col-12">
		<div class="d-flex justify-content-between align-items-center">
			<h2 class="text-primary fw-bold">Statistiche e Analisi AI</h2>
		</div>
		<p class="text-muted">
			In questa sezione puoi visualizzare le verifiche effettuate e accedere alla pagina di dettaglio per generare o consultare l'analisi dell'Intelligenza Artificiale sugli errori più comuni.
			Espandi una riga per visualizzare le correzioni collegate alla verifica principale e alle eventuali sottoverifiche.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card card-outline card-primary shadow-sm">
			<div class="card-body">
				<table class="table table-striped w-100" id="statistiche-table">
					<thead>
						<tr>
							<th></th>
							<th>ID</th>
							<th>Titolo Verifica Principale</th>
							<th>Classe</th>
							<th>Materia</th>
							<th style="width: 15%;">Azioni</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($verifiche as $verifica){
								// Mostra solo le verifiche principali
								if($verifica->ID_verifica != null) continue;
								?>
								<tr>
									<td></td>
									<td><?php echo $verifica->id; ?></td>
									<td class="fw-bold"><?php echo $verifica->titolo; ?></td>
									<td><?php echo $verifica->classe->getNomeClasse(); ?></td>
									<td><?php echo $verifica->materia->nome; ?></td>
									<td>
										<a class="btn btn-primary btn-sm" href="/pages/statistiche/dettaglio?id=<?php echo $verifica->id; ?>">
											Apri Dettaglio
										</a>
									</td>
								</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
