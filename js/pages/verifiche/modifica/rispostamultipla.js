let modal_rispostamultipla = null;
let modal_elimina_rispostamultipla = null;
let modal_elimina_risposta = null;
let modal_risposta = null

let modal_rispostamultipla_init = (id_sezione = 0, show = true) => {
	if (modal_rispostamultipla == null) {
		modal_rispostamultipla = new bootstrap.Modal("#modal-rispostamultipla");
	}

	$("#form-rispostamultipla [name=ID_sezione]").val(id_sezione);
	$("#form-rispostamultipla [name=id]").val(0);
	$("#form-rispostamultipla [name=type]").val("modifica-rispostamultipla");

	modal_rispostamultipla._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-rispostamultipla")[0].reset();
	});

	if(show)
		modal_rispostamultipla.show();
}

let modal_elimina_rispostamultipla_init = () => {
	if (modal_elimina_rispostamultipla == null) {
		modal_elimina_rispostamultipla = new bootstrap.Modal("#modal-elimina-rispostamultipla");
	}
	modal_elimina_rispostamultipla._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-rispostamultipla")[0].reset();
		$("#form-elimina-rispostamultipla [name=type]").val("elimina-rispostamultipla");
	})
}

let modal_risposta_init = () => {
	if (modal_risposta == null) {
		modal_risposta = new bootstrap.Modal("#modal-risposta");
	}
	modal_risposta._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-risposta")[0].reset();
		$("#form-risposta [name=type]").val("modifica-risposta");
	})
}

let modal_elimina_risposta_init = () => {
	if (modal_elimina_risposta == null) {
		modal_elimina_risposta = new bootstrap.Modal("#modal-elimina-risposta");
	}
	modal_elimina_risposta._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-risposta")[0].reset();
		$("#form-elimina-risposta [name=type]").val("elimina-risposta");
	})
}



$(document).ready(function () {

	$(".modifica-rispostamultipla").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-rispostamultipla");

		modal_rispostamultipla_init(0, false);

		$.ajax({
			url: "/api/rispostamultipla/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				$("#form-rispostamultipla [name=testo]").val(data.testo);
				$("#form-rispostamultipla [name=id]").val(id);
				$("#form-rispostamultipla [name=punteggio]").val(data.punteggio);
				$("#form-rispostamultipla [name=note]").text(data.note);
				$("#form-rispostamultipla [name=type]").val("modifica-rispostamultipla");
			}
		});

		modal_rispostamultipla.show();
	});

	$(".elimina-rispostamultipla").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-rispostamultipla");

		modal_elimina_rispostamultipla_init();

		$("#form-elimina-rispostamultipla [name=id]").val(id);

		modal_elimina_rispostamultipla.show();
	});

	$(".aggiungi-risposta").on("click", (e) => {
		let button = e.currentTarget;
		let id_rispostamultipla = $(button).attr("id-rispostamultipla");

		modal_risposta_init();

		$("#form-risposta [name=ID_rispostamultipla]").val(id_rispostamultipla);
		$("#form-risposta [name=id]").val(0);
		$("#form-risposta [name=type]").val("modifica-risposta");
		$("#modal-risposta-titolo").text("Nuova Risposta");

		modal_risposta.show();
	});

	$(".modifica-risposta").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).attr("id-risposta");
        let id_rispostamultipla = $(button).parents(".esercizio").attr("id-rispostamultipla");

		modal_risposta_init();

		$.ajax({
			url: "/api/rispostamultipla/risposta/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				$("#form-risposta [name=testo]").val(data.testo);
				$("#form-risposta [name=id]").val(id);
                $("#form-risposta [name=ID_rispostamultipla]").val(id_rispostamultipla);
				$("#form-risposta [name=corretto]").prop("checked", data.corretto == true);
				$("#form-risposta [name=punteggio]").val(data.punteggio);
				$("#form-risposta [name=type]").val("modifica-risposta");
				$("#modal-risposta-titolo").text("Modifica Risposta");
			}
		});

		modal_risposta.show();
	});

	$(".elimina-risposta").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).attr("id-risposta");

		modal_elimina_risposta_init();

		$("#form-elimina-risposta [name=id]").val(id);

		modal_elimina_risposta.show();
	})
})