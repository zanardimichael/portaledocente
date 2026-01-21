
$(document).ready(function () {

	let inputChange = function (event) {
		let parent_form = $(event.currentTarget).parents("form");
		let id = parent_form.attr("id");

		$.ajax({
			url: parent_form.attr("action"),
			type: "PATCH",
			dataType: "json",
			data: parent_form.serialize(),
			success: function (data) {
				parent_form.load(" #correzione-"+id, () => {
					parent_form.find("input").on("change", inputChange);
					parent_form.find(".parziale").on("change", parzialeChange);
					$("#voto-outer").load(" #voto-inner");
				});
			}
		})
	};

	let parzialeChange = function (event) {
		let parent_form = $(event.currentTarget).parents("form");
		if(event.target.checked) {
			parent_form.find(".punteggio-parziale").prop("disabled", false);
		}else {
			parent_form.find(".punteggio-parziale").prop("disabled", true).val("0");
		}
	}

	$("form").on("submit", function (event) {
		event.preventDefault();
	})

	$(".parziale").on("change", parzialeChange);

	$("input").on("change", inputChange);
})