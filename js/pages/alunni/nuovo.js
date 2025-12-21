$(document).ready(function(){
	$("#nome, #cognome").on("keyup", () => {
		if($("#emailAutogenerata").is(":checked")) {
			let email = $("#nome").val().toLowerCase() + "." + ($("#cognome").val().split(" ")[0]).toLowerCase();
			$("#email").val(email);
		}
	});

	$("#emailAutogenerata").on("click", () => {
		if($("#emailAutogenerata").is(":checked")) {
			$("#email").prop("readonly", true);
		}else{
			$("#email").prop("readonly", false);
		}
	})
})