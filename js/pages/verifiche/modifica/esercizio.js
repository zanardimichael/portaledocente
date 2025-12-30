let modal_esercizio = null;
let modal_elimina_esercizio = null;

let modal_esercizio_init = (id_sezione = 0, show = true) => {
	if (modal_esercizio == null) {
		modal_esercizio = new bootstrap.Modal("#modal-esercizio");
	}

	$("#form-esercizio [name=ID_sezione]").val(id_sezione);
	$("#form-esercizio [name=id]").val(0);
	$("#form-esercizio [name=type]").val("modifica-esercizio");
	$("#form-esercizio [name=testo]").summernote();

	modal_esercizio._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-esercizio")[0].reset();
	});

	if(show)
		modal_esercizio.show();
}

let modal_elimina_esercizio_init = () => {
	if (modal_elimina_esercizio == null) {
		modal_elimina_esercizio = new bootstrap.Modal("#modal-elimina-esercizio");
	}
	modal_elimina_esercizio._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-esercizio")[0].reset();
		$("#form-elimina-esercizio [name=type]").val("elimina-esercizio");
	})
}


$(document).ready(function () {

	$(".modifica-esercizio").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-esercizio");

		modal_esercizio_init(0, false);

		$.ajax({
			url: "/api/esercizio/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#form-esercizio [name=titolo]").val(data.titolo);
				$("#form-esercizio [name=testo]").summernote('code', data.testo);
				$("#form-esercizio [name=id]").val(id);
				$("#form-esercizio [name=punteggio]").val(data.punteggio);
				$("#form-esercizio [name=note]").text(data.note);
				$("#form-esercizio [name=type]").val("modifica-esercizio");
			}
		});

		modal_esercizio.show();
	});

	$(".elimina-esercizio").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).parents(".esercizio").attr("id-esercizio");

		modal_elimina_esercizio_init();

		$("#form-elimina-esercizio [name=id]").val(id);

		modal_elimina_esercizio.show();
	})
})