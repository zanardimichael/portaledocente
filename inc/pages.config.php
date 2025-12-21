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
		],
		"classi/visualizza" => [
			"url" => "/pages/classi/visualizza",
			"title" => "Visualizza Classe",
			"back_button" => true
		],
		"alunni" => [
			"url" => "/pages/alunni",
			"title" => "Alunni",
			"icon" => "bi bi-person-vcard",
			"script_js" => [
				"/js/pages/alunni.js"
			]
		],
		"alunni/nuovo" => [
			"url" => "/pages/alunni/nuovo",
			"title" => "Nuovo Alunno",
			"back_button" => true
		],
		"alunni/modifica" => [
			"url" => "/pages/alunni/modifica",
			"title" => "Modifica Alunno",
			"back_button" => true
		],
		"alunni/elimina" => [
			"url" => "/pages/alunni/elimina",
			"title" => "Elimina Alunno",
			"back_button" => true
		],
		"alunni/visualizza" => [
			"url" => "/pages/alunni/visualizza",
			"title" => "Visualizza Alunno",
			"back_button" => true
		],
	];