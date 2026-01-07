new DataTable('#verifiche-table', {
    language: lang,
	layout: {
		topStart: "buttons",
		bottomStart: "pageLength"
	},
	buttons: [{
		text: "Nuova",
		className: "btn btn-success",
		init: function(api, node, config) {
			$(node).removeClass('dt-button')
		},
		action: function () {
			window.location.href = '/pages/verifiche/nuova';
		}
	}
	]
});