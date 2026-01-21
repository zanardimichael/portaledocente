let table = new DataTable('#verifiche-table', {
	language: lang,
	select: "single",
	columns: [
		{
			data: "id",
			name: "id",
			visible: false
		},
		{
			data: "titolo",
			name: "Titolo",
		},
		{
			data: "classe",
			name: "Classe",
		},
		{
			data: "materia",
			name: "Materia",
		},
	]
});

$(document).ready(function () {
	$("#form-nuova-correzione").on("submit", function (e) {
		if(e.currentTarget.checkValidity()) {
			let data = table.rows({selected: true}).data();
			if (data.length === 1) {
				$("#ID_verifica").val(data[0].id);
			}else{
				e.preventDefault();
				Toastify({
					text: "Seleziona la verifica da correggere",
					duration: 3000,
					style: {background: "var(--bs-warning)"},
				}).showToast();
			}
		}else{
			e.preventDefault();
		}
	})
})