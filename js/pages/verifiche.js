let table = new DataTable('#verifiche-table', {
    language: lang,
	layout: {
		topStart: "buttons",
		bottomStart: "pageLength"
	},
	buttons: [{
		text: "Nuova",
		className: "btn btn-success",
		init: function(api, node, config) {
			$(node).removeClass('dt-button')
		},
		action: function () {
			window.location.href = '/pages/verifiche/nuova';
		}
	}],
	columns: [
		{
			className: 'dt-control',
			orderable: false,
			data: null,
			defaultContent: ''
		},
		{ data: "id", visible: false},
		{ data: 'titolo' },
		{ data: 'classe' },
		{ data: 'materia' },
		{ data: 'azioni' }
	],
});
table.on('click', 'tbody td.dt-control', function (e) {
	let tr = e.target.closest('tr');
	let row = table.row(tr);

	if (row.child.isShown()) {
		// This row is already open - close it
		row.child.hide();
	}
	else {
		let d = row.data();

		row.child("<div class=\"spinner-border text-primary\" role=\"status\">\n" +
			"  <span class=\"visually-hidden\">Loading...</span>\n" +
			"</div>").show();

		$.ajax({
			url: "/api/sottoverifica/"+d.id,
			method: "GET",
			success: function (data) {
				let str = "";
				if(data.length > 0) {
					str = `<div class="row">
						<div class="col-6">
							<h5>Sotto-verifiche</h5>
						</div>
						<div class="col-6">
							<div class="float-end">
								<a class="btn btn-success" href="/pages/verifiche/nuova?ID_verifica=${data[0].ID_verifica}">Nuova Sotto-verifica</a>
							</div>
						</div>
						<hr class=\'mt-1 mb-1\'>`;
					for (let i=0; i < data.length; i++) {
						str += `<div class="col-6">
									<div>${data[i].titolo}</div>
								</div>
								<div class="col-6">
									<div class='float-end'>
										<a class="btn btn-primary" href="/pages/verifiche/modifica?id=${data[i].id}"><i class="bi bi-pencil-square"></i></a>
										<a class="btn btn-danger" href="/pages/verifiche/elimina?id=${data[i].id}"><i class="bi bi-trash"></i></a>
										<a class="btn btn-success" href="/download.php?type=verifica_latex&id=${data[i].id}"><i class="bi bi-download"></i></a>
									</div>
								</div>`;
						if(i != data.length - 1) {
							str += "<hr class='mt-1 mb-1'>";
						}
					}
					str += '</div>';
				}else{
					str = "Nessuna sotto-verifica associata";
				}
				row.child(str).show();
			}
		});
	}
});