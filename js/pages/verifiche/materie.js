
$(document).ready(() => {

	$("#form-verifica #classe").on("change", () => {

		$.ajax({
			url: "/api/materia/"+$("#form-verifica [name=classe] option:selected").val()+"/"+ID_professore,
			method: "GET",
			dataType: "json",
			success: (response) => {
				let materia_el = $("#form-verifica [name=materia]");
				materia_el.html("<option selected disabled value=\"\">Scegli...</option>");
				for(let i = 0; i < response.length; i++) {
					let option = $("<option></option>");
					option.val(response[i].id);
					option.text(response[i].nome);
					materia_el.append(option);
				}
			}
		})
	});
});