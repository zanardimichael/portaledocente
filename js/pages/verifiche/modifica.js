let modal_general = null;

let modal_general_init = () => {
	modal_general._element.addEventListener('hidden.bs.modal', (e) => {
		$("#form-general")[0].reset();
	})
}

$(document).ready(() => {

	$(".aggiungi-esercizio").on("click", (e) => {
		if (modal_general == null) {
			modal_general = new bootstrap.Modal("#modal-general");
			modal_general_init();
		}

		let button = e.currentTarget;
		let id = $(button).attr("id-sezione");

		$("#form-general [name=ID_sezione]").val(id);

		modal_general.show();
	});

	$("#form-general").on("submit", (e) => {
		e.preventDefault();

		if(e.currentTarget.checkValidity()) {

			let id_sezione = $("#form-general [name=ID_sezione]").val();
			let tipologia = $("#form-general [name=tipologia]").find(":selected").val();

			switch (tipologia) {
				case "verofalso":
					modal_verofalso_init(id_sezione);
					break;
				case "rispostamultipla":
					modal_rispostamultipla_init(id_sezione);
					break;
				case "rispostaaperta":
					modal_rispostaaperta_init(id_sezione);
					break;
				case "esercizio":
					break;
			}

			modal_general.hide();
		}
	});


		// Example starter JavaScript for disabling form submissions if there are invalid fields
	(() => {
		'use strict';

		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		const forms = document.querySelectorAll('.needs-validation');

		// Loop over them and prevent submission
		Array.from(forms).forEach((form) => {
			form.addEventListener(
				'submit',
				(event) => {
					if (!form.checkValidity()) {
						event.preventDefault();
						event.stopPropagation();
					}

					form.classList.add('was-validated');
				},
				false,
			);
		});
	})();
})