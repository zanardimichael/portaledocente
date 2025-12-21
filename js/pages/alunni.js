new DataTable('#alunni-table', {
    language: lang,
    layout: {
        topStart: "buttons",
        bottomStart: "pageLength"
    },
    buttons: [{
        text: "Nuovo",
        className: "btn btn-success",
        init: function(api, node, config) {
            $(node).removeClass('dt-button')
        },
        action: function () {
            window.location.href = '/pages/alunni/nuovo';
        }
    }
    ]
});