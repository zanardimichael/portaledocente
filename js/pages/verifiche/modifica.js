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
					modal_esercizio_init(id_sezione);
					break;
			}

			modal_general.hide();
		}
	});

	$(".ordina-giu-sezione, .ordina-su-sezione").on("click", (e) => {
		let id_sezione = $(e.currentTarget).attr("id-sezione");
		let sezione = $(".sezione[id-sezione="+id_sezione+"]");
		let giu = $(e.currentTarget).hasClass("ordina-giu-sezione");
		let ordine = parseInt(sezione.attr("ordine"));
		let precedente = sezione;
		let successivo = $(".sezione[ordine="+(ordine+1)+"]");

		if(!giu){
			precedente = $(".sezione[ordine="+(ordine-1)+"]");
			successivo = sezione;
			id_sezione = precedente.attr("id-sezione");
		}else{
			ordine++;
		}
		console.log(ordine);

		$.ajax({
			url: "/api/sezione/"+id_sezione+"/invertiordine",
			method: "PATCH",
			dataType: "json",
			success: function (data) {
				if(data.result) {
					successivo.find("button.ordina-giu-sezione, button.ordina-su-sezione").prop("disabled", false);
					precedente.find("button.ordina-giu-sezione, button.ordina-su-sezione").prop("disabled", false);
					successivo.attr("ordine", ordine-1);
					precedente.attr("ordine", ordine);
					//se il precedente ha ordine == 1
					if(ordine == 2) successivo.find("button.ordina-su-sezione").prop("disabled", true);
					if(ordine == ordine_sezione_max) precedente.find("button.ordina-giu-sezione").prop("disabled", true);
					successivo.insertBefore(precedente);
				}
			}
		})
	});

	$(".ordina-su-esercizio, .ordina-giu-esercizio").on("click", (e) => {
		let id_sezione = $(e.currentTarget).parents(".sezione").attr("id-sezione");
		let giu = $(e.currentTarget).hasClass("ordina-giu-esercizio");
		let esercizio = $(e.currentTarget).parents(".esercizio");
		let ordine = parseInt(esercizio.attr("ordine"));

		let precedente = esercizio;
		let successivo = $(".sezione[id-sezione="+id_sezione+"] .esercizio[ordine="+(ordine+1)+"]");

		if(!giu) {
			precedente = $(".sezione[id-sezione="+id_sezione+"] .esercizio[ordine="+(ordine-1)+"]");
			successivo = esercizio;
			ordine--;
		}

		let max_esercizi = $(".sezione[id-sezione="+id_sezione+"] .esercizio").length;

		console.log("Giu", giu);
		console.log("Precedente", precedente);
		console.log("Successivo", successivo);
		console.log(ordine);

		$.ajax({
			url: "/api/sezione/" + id_sezione + "/invertiOrdineEsercizio/" + ordine,
			method: "PATCH",
			dataType: "json",
			success: function (data) {
				console.log(data);
				if (data.result) {
					successivo.find("button.ordina-giu-esercizio, button.ordina-su-esercizio").prop("disabled", false);
					precedente.find("button.ordina-giu-esercizio, button.ordina-su-esercizio").prop("disabled", false);
					successivo.attr("ordine", ordine);
					precedente.attr("ordine", ordine+1);
					//se il precedente ha ordine == 1
					if(ordine == 1) successivo.find("button.ordina-su-esercizio").prop("disabled", true);
					if(ordine == max_esercizi-1) precedente.find("button.ordina-giu-esercizio").prop("disabled", true);
					successivo.insertBefore(precedente);
				}
			}
		});
	});

    if(typeof redirect_url !== 'undefined'){
        if(redirect_url.charAt(0) == "#"){
            location.hash = redirect_url;
        }else{
            location.href = redirect_url;
        }
    }


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