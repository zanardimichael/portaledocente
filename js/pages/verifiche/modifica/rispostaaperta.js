let modal_rispostaaperta = null;
let modal_elimina_rispostaaperta = null;

let modal_rispostaaperta_init = (id_sezione = 0, show = true) => {
	if (modal_rispostaaperta == null) {
		modal_rispostaaperta = new bootstrap.Modal("#modal-rispostaaperta");
	}

	$("#form-rispostaaperta [name=ID_sezione]").val(id_sezione);
	$("#form-rispostaaperta [name=id]").val(0);
	$("#form-rispostaaperta [name=type]").val("modifica-rispostaaperta");

	modal_rispostaaperta._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-rispostaaperta")[0].reset();
	});

	if(show)
		modal_rispostaaperta.show();
}

let modal_elimina_rispostaaperta_init = () => {
	if (modal_elimina_rispostaaperta == null) {
		modal_elimina_rispostaaperta = new bootstrap.Modal("#modal-elimina-rispostaaperta");
	}
	modal_elimina_rispostaaperta._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-rispostaaperta")[0].reset();
		$("#form-elimina-rispostaaperta [name=type]").val("elimina-rispostaaperta");
	})
}


$(document).ready(function () {

	$(".modifica-rispostaaperta").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-rispostaaperta");

		modal_rispostaaperta_init(0, false);

		$.ajax({
			url: "/api/rispostaaperta/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				$("#form-rispostaaperta [name=testo]").val(data.testo);
				$("#form-rispostaaperta [name=id]").val(id);
				$("#form-rispostaaperta [name=punteggio]").val(data.punteggio);
				$("#form-rispostaaperta [name=note]").text(data.note);
				$("#form-rispostaaperta [name=type]").val("modifica-rispostaaperta");
			}
		});

		modal_rispostaaperta.show();
	});

	$(".elimina-rispostaaperta").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-rispostaaperta");

		modal_elimina_rispostaaperta_init();

		$("#form-elimina-rispostaaperta [name=id]").val(id);

		modal_elimina_rispostaaperta.show();
	})
})