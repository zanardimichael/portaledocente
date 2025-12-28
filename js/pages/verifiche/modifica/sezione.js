let modal_sezione = null;
let modal_elimina_sezione = null;

let modal_sezione_init = () => {
	if (modal_sezione == null) {
		modal_sezione = new bootstrap.Modal("#modal-sezione");
	}
	modal_sezione._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-sezione")[0].reset();
		$("#form-elimina-sezione [name=type]").val("modifica-sezione");
	})
}

let modal_elimina_sezione_init = () => {
	if (modal_elimina_sezione == null) {
		modal_elimina_sezione = new bootstrap.Modal("#modal-elimina-sezione");
	}
	modal_elimina_sezione._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-elimina-sezione")[0].reset();
		$("#form-elimina-sezione [name=type]").val("elimina-sezione");
	})
}


$(document).ready(() => {

	$(".modifica-sezione").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).attr("id-sezione");

		modal_sezione_init();

		$("#modal-sezione-titolo").text("Modifica Sezione");
		$("#form-sezione [name=type]").val("modifica-sezione");

		$.ajax({
			url: "/api/sezione/" + id,
			method: "GET",
			dataType: "json",
			success: function (data) {
				$("#form-sezione [name=titolo]").val(data.titolo);
				$("#form-sezione [name=id]").val(id);
				$("#form-sezione [name=ID_verifica]").val(ID_verifica);
			}
		});

		modal_sezione.show();
	});

	$(".aggiungi-sezione").on("click", () => {
		modal_sezione_init();

		$("#form-sezione [name=id]").val(0);
		$("#form-sezione [name=ID_verifica]").val(ID_verifica);
		$("#modal-sezione-titolo").text("Nuova Sezione");

		modal_sezione.show();
	});

	$(".elimina-sezione").on("click", (e) => {
		let button = e.currentTarget;
		let id = $(button).attr("id-sezione");

		modal_elimina_sezione_init();

		$("#form-elimina-sezione [name=id]").val(id);
		$("#form-elimina-sezione [name=type]").val("elimina-sezione");

		modal_elimina_sezione.show();
	});
});