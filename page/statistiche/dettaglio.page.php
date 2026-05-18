<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	global $page;
	
	$id_verifica = $_GET['id'] ?? null;
	if (!$id_verifica || !Verifica::exists($id_verifica)) {
		echo "<div class='alert alert-danger'>Verifica non trovata.</div>";
		return;
	}
	
	$verifica = new Verifica($id_verifica);
?>
<div class="row mb-4">
	<div class="col-12">
		<div class="d-flex justify-content-between align-items-center">
			<h2 class="text-primary fw-bold">Analisi AI: <?php echo $verifica->titolo; ?></h2>
		</div>
		<p class="text-muted">
			Questa pagina mostra tutte le correzioni effettuate per la verifica principale e le relative sottoverifiche.
			Puoi generare e consultare l'analisi dell'Intelligenza Artificiale per ogni singola correzione.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<!-- ID della verifica madre passato come attributo data per il JS -->
		<div id="correzioni-list-container" class="accordion" data-verifica-id="<?php echo $verifica->id; ?>">
			<div class="text-center py-5" id="correzioni-loading">
				<div class="spinner-border text-primary" role="status">
					<span class="visually-hidden">Caricamento...</span>
				</div>
				<p class="mt-2 text-muted">Caricamento correzioni in corso...</p>
			</div>
		</div>
	</div>
</div>

<template id="correzione-card-template">
	<div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
		<h2 class="accordion-header">
			<button class="accordion-button collapsed bg-white text-dark fw-bold" type="button" data-bs-toggle="collapse">
				<span class="me-3"><i class="bi bi-file-earmark-text text-primary"></i></span>
				<span class="titolo-verifica me-auto"></span>
				<span class="badge data-verifica me-3"></span>
			</button>
		</h2>
		<div class="accordion-collapse collapse bg-light">
			<div class="accordion-body">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<span class="text-muted note-verifica"></span>
					<div>
						<button class="btn btn-primary btn-sm btn-analyze" data-id="">
							<i class="bi bi-magic"></i> <span class="btn-text">Genera Analisi AI</span>
						</button>
					</div>
				</div>
				
				<div class="ai-analysis-container d-none bg-white p-4 rounded border shadow-sm">
					<div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
						<h5 class="m-0 text-success"><i class="bi bi-check-circle"></i> Analisi Completata</h5>
					</div>
					<div class="ai-analysis-result markdown-body" style="font-size: 1.05rem; line-height: 1.6;">
					</div>
				</div>
				
				<div class="ai-loading-container d-none text-center py-4">
					<div class="spinner-grow text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
						<span class="visually-hidden">Elaborazione...</span>
					</div>
					<h6 class="mt-3 text-muted">Gemini sta analizzando gli errori...</h6>
				</div>
				
				<div class="ai-error-container d-none alert alert-danger mt-3">
					<i class="bi bi-exclamation-triangle"></i> <span class="ai-error-message"></span>
				</div>
			</div>
		</div>
	</div>
</template>

<style>
	/* Premium Styles */
	.accordion-button:not(.collapsed) {
		background-color: #f8f9fa;
		color: #0d6efd;
		box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
	}
	.accordion-item {
		transition: transform 0.2s ease, box-shadow 0.2s ease;
		border: 1px solid rgba(0,0,0,.05) !important;
	}
	.accordion-item:hover {
		transform: translateY(-2px);
		box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
	}
	.markdown-body h3, .markdown-body h4, .markdown-body h5 { color: #0d6efd; margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600; }
	.markdown-body ul { list-style-type: none; padding-left: 0; }
	.markdown-body ul li { position: relative; padding-left: 1.5rem; margin-bottom: 0.5rem; }
	.markdown-body ul li::before { content: "•"; color: #0d6efd; font-weight: bold; position: absolute; left: 0; font-size: 1.2em; top: -2px; }
	.markdown-body strong { color: #212529; font-weight: 600; }
	.btn-analyze { transition: all 0.3s ease; }
	.btn-analyze:hover { transform: scale(1.03); box-shadow: 0 4px 8px rgba(13,110,253,0.2); }
</style>
