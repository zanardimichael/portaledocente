let modal_alunni = null;
let datatable_alunni = null;

new DataTable('#alunni-table', {
    language: lang,
	layout: {
		topStart: "buttons",
		bottomStart: "pageLength"
	},
	buttons: [{
		text: "Aggiungi alunno",
		className: "btn btn-success",
		init: function(api, node, config) {
			$(node).removeClass('dt-button')
		},
		action: function () {
			$("#form-alunni input[name=id]").val(ID_correzione);

			if(modal_alunni == null) {
				modal_alunni = new bootstrap.Modal("#modal-alunni");
			}
			modal_alunni.show();
			if(datatable_alunni == null) {
				datatable_alunni = new DataTable("#aggiunta-alunni-table", {
					language: lang,
					select: "multi",
					columns: [
						{
							data: "id",
							name: "id",
							visible: false
						},
						{
							data: "nome",
							name: "Nome",
						},
					]
				});
			}
			$(".aggiungi-alunni").on('click', function(e) {
				let data = datatable_alunni.rows({selected: true}).data();
				let ids = [];
				for(let i = 0; i < data.length; i++) {
					ids.push(data[i].id);
				}
				let alunni = ids.join(",");
				$("#form-alunni input[name=alunni]").val(alunni);
				$("#form-alunni").submit();
			})
		}
	}
	]
});