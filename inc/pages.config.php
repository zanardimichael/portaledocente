<?php

	$pages = [
		"dashboard" => [
			"url" => "/",
			"title" => "Dashboard",
			"icon" => "bi bi-speedometer",
			"script_js" => []
		],
		"portaledocente" => [
			"separator" => true,
			"title" => "Portale Docente",
		],
		"classi" => [
			"url" => "/pages/classi",
			"title" => "Classi",
			"icon" => "bi bi-mortarboard",
			"script_js" => [
				"/js/pages/classi.js"
			]
		],
		"classi/nuova" => [
			"url" => "/pages/classi/nuova",
			"title" => "Nuova Classe",
			"back_button" => true
		],
		"classi/modifica" => [
			"url" => "/pages/classi/modifica",
			"title" => "Modifica Classe",
			"back_button" => true
		],
		"classi/elimina" => [
			"url" => "/pages/classi/elimina",
			"title" => "Elimina Classe",
			"back_button" => true
		]
	];