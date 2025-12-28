let modal_verofalso = null;
let modal_elimina_verofalso = null;

let modal_verofalso_init = (id_sezione = 0, show = true) => {
	if (modal_verofalso == null) {
		modal_verofalso = new bootstrap.Modal("#modal-verofalso");
	}

	$("#form-verofalso [name=ID_sezione]").val(id_sezione);
	$("#form-verofalso [name=id]").val(0);
	$("#form-verofalso [name=type]").val("modifica-verofalso");

	modal_verofalso._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-verofalso")[0].reset();
	});

	if(show)
		modal_verofalso.show();
}

let modal_elimina_verofalso_init = () => {
	if (modal_elimina_verofalso == null) {
		modal_elimina_verofalso = new bootstrap.Modal("#modal-elimina-verofalso");
	}
	modal_elimina_verofalso._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-verofalso")[0].reset();
		$("#form-elimina-verofalso [name=type]").val("elimina-verofalso");
	})
}


$(document).ready(function () {

	$(".modifica-verofalso").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-verofalso");

		modal_verofalso_init(0, false);

		$.ajax({
			url: "/api/verofalso/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				$("#form-verofalso [name=testo]").val(data.testo);
				$("#form-verofalso [name=risultato][value="+(data.risultato ? "1": "0")+"]").prop("checked", true);
				$("#form-verofalso [name=id]").val(id);
				$("#form-verofalso [name=punteggio]").val(data.punteggio);
				$("#form-verofalso [name=note]").text(data.note);
				$("#form-verofalso [name=type]").val("modifica-verofalso");
			}
		});

		modal_verofalso.show();
	});

	$(".elimina-verofalso").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-verofalso");

		modal_elimina_verofalso_init();

		$("#form-elimina-verofalso [name=id]").val(id);

		modal_elimina_verofalso.show();
	})
})