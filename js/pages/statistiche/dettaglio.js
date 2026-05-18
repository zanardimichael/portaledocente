$(document).ready(function() {
	
	const listContainer = $('#correzioni-list-container');
	const loadingIndicator = $('#correzioni-loading');
	const template = $('#correzione-card-template').html();
	const idVerifica = listContainer.data('verifica-id');
	
	// Utilizza marked se disponibile, altrimenti fallback a formattazione base
	function formatMarkdown(text) {
		if (typeof marked !== 'undefined') {
			return marked.parse(text);
		}
		let html = text
			.replace(/^### (.*$)/gim, '<h5>$1</h5>')
			.replace(/^## (.*$)/gim, '<h4>$1</h4>')
			.replace(/^# (.*$)/gim, '<h3>$1</h3>')
			.replace(/\*\*(.*)\*\*/gim, '<strong>$1</strong>')
			.replace(/\*(.*)\*/gim, '<em>$1</em>');
		
		// Gestione liste
		html = html.replace(/^\* (.*$)/gim, '<li>$1</li>');
		html = html.replace(/<\/li>\n<li>/gim, '</li><li>');
		
		return html.replace(/\n/gim, '<br>');
	}
	
	// Carica le correzioni per la verifica corrente
	if (idVerifica) {
		$.ajax({
			url: '/api/verifica/' + idVerifica + '/correzioni',
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				loadingIndicator.addClass('d-none');
				
				if (response.success && response.data.length > 0) {
					response.data.forEach((corr) => {
						let item = $(template);
						
						// Setup id e collapse
						let collapseId = 'collapse-' + corr.id;
						item.find('.accordion-button')
							.attr('data-bs-target', '#' + collapseId)
							.attr('aria-controls', collapseId);
						item.find('.accordion-collapse').attr('id', collapseId);
						
						// Inserimento dati
						let badgeType = corr.is_sottoverifica ? 'bg-info text-dark' : 'bg-primary';
						let typeLabel = corr.is_sottoverifica ? 'Sottoverifica' : 'Principale';
						
						item.find('.titolo-verifica').html(corr.titolo + ' <span class="badge ms-2 ' + badgeType + '">' + typeLabel + '</span>');
						item.find('.data-verifica').addClass('bg-secondary rounded-pill').text(corr.data);
						if (corr.note) {
							item.find('.note-verifica').text(corr.note);
						}
						
						item.find('.btn-analyze').attr('data-id', corr.id);
						
						// Se c'è un'analisi salvata, mostrala
						if (corr.analisi_ai) {
							item.find('.ai-analysis-result').html(formatMarkdown(corr.analisi_ai));
							item.find('.ai-analysis-container').removeClass('d-none');
							item.find('.btn-text').text('Rigenera Analisi');
							item.find('.btn-analyze').removeClass('btn-primary').addClass('btn-outline-primary');
						}
						
						listContainer.append(item);
					});
				} else {
					listContainer.append('<div class="alert alert-info">Nessuna correzione effettuata per questa verifica.</div>');
				}
			},
			error: function() {
				loadingIndicator.addClass('d-none');
				listContainer.append('<div class="alert alert-danger">Errore durante il caricamento delle correzioni.</div>');
			}
		});
	}
	
	// Gestione click bottone analizza/rigenera
	$(document).on('click', '.btn-analyze', function() {
		let btn = $(this);
		let id = btn.attr('data-id');
		let container = btn.closest('.accordion-body');
		
		let resultContainer = container.find('.ai-analysis-container');
		let loadingContainer = container.find('.ai-loading-container');
		let errorContainer = container.find('.ai-error-container');
		let textResult = container.find('.ai-analysis-result');
		let textError = container.find('.ai-error-message');
		
		// Setup UI
		btn.prop('disabled', true);
		resultContainer.addClass('d-none');
		errorContainer.addClass('d-none');
		loadingContainer.removeClass('d-none');
		
		$.ajax({
			url: '/api/statistiche/ai?correzioni=' + id,
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				loadingContainer.addClass('d-none');
				btn.prop('disabled', false);
				
				if(response.error) {
					textError.text(response.error);
					errorContainer.removeClass('d-none');
					return;
				}
				
				if(response.success && response.data) {
					textResult.html(formatMarkdown(response.data));
					resultContainer.removeClass('d-none');
					btn.find('.btn-text').text('Rigenera Analisi');
					btn.removeClass('btn-primary').addClass('btn-outline-primary');
				} else {
					textError.text("Risposta non valida dal server.");
					errorContainer.removeClass('d-none');
				}
			},
			error: function(xhr, status, error) {
				loadingContainer.addClass('d-none');
				btn.prop('disabled', false);
				
				let errorMsg = "Errore di connessione o errore sul server.";
				if (xhr.responseJSON && xhr.responseJSON.error) {
					errorMsg = xhr.responseJSON.error;
				}
				
				textError.text(errorMsg);
				errorContainer.removeClass('d-none');
			}
		});
	});
});
