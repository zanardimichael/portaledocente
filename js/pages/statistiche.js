let table = new DataTable('#statistiche-table', {
	language: lang,
	layout: {
		topStart: "buttons",
		bottomStart: "pageLength"
	},
	buttons: [],
	columns: [
		{
			className: 'dt-control',
			orderable: false,
			data: null,
			defaultContent: ''
		},
		{data: "id", visible: false},
		{data: 'titolo'},
		{data: 'classe'},
		{data: 'materia'},
		{data: 'azioni', orderable: false}
	],
});

table.on('click', 'tbody td.dt-control', function (e) {
	let tr = e.target.closest('tr');
	let row = table.row(tr);

	if (row.child.isShown()) {
		row.child.hide();
	} else {
		let d = row.data();

		row.child("<div class=\"spinner-border text-primary\" role=\"status\">\n" +
			"  <span class=\"visually-hidden\">Loading...</span>\n" +
			"</div>").show();

		$.ajax({
			url: "/api/verifica/" + d.id + "/correzioni",
			method: "GET",
			success: function (response) {
				if (response.success) {
					let data = response.data;
					let str = "";
					if (data.length > 0) {
						str = `<div class="p-3 bg-light rounded border">
							<div class="row">
								<div class="col-12">
									<h6 class="text-primary mb-3"><i class="bi bi-list-check"></i> Elenco Correzioni associate a questa Verifica</h6>
								</div>
							</div>`;
						
						for (let i = 0; i < data.length; i++) {
							let corr = data[i];
							let badge = corr.is_sottoverifica ? '<span class="badge bg-info text-dark">Sottoverifica</span>' : '<span class="badge bg-secondary">Principale</span>';
							let textPreview = corr.analisi_ai ? '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Analisi Presente</span>' : '<span class="text-warning text-dark"><i class="bi bi-clock"></i> Nessuna Analisi</span>';
							
							str += `<div class="row mb-2 align-items-center">
										<div class="col-6">
											<strong>${corr.titolo}</strong> ${badge}<br>
											<small class="text-muted"><i class="bi bi-calendar"></i> ${corr.data} ${corr.note ? ' - ' + corr.note : ''}</small>
										</div>
										<div class="col-3">
											${textPreview}
										</div>
										<div class="col-3 text-end">
											<a class="btn btn-sm btn-outline-primary" href="/pages/statistiche/dettaglio?id=${d.id}">Vai al Dettaglio <i class="bi bi-arrow-right"></i></a>
										</div>
									</div>`;
							if (i !== data.length - 1) {
								str += "<hr class='mt-2 mb-2 text-muted'>";
							}
						}
						str += `</div>`;
					} else {
						str = `<div class="row p-3">
							<div class="col-12 text-muted">
								<i class="bi bi-info-circle"></i> Nessuna correzione trovata per questa verifica e le sue sottoverifiche.
							</div>
						</div>`;
					}
					row.child(str).show();
				} else {
					row.child("<div class='text-danger p-3'><i class=\"bi bi-exclamation-triangle\"></i> Errore nel caricamento delle correzioni.</div>").show();
				}
			},
			error: function() {
				row.child("<div class='text-danger p-3'><i class=\"bi bi-exclamation-triangle\"></i> Errore di comunicazione col server.</div>").show();
			}
		});
	}
});
