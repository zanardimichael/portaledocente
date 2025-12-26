<?php

	$pages = [
		"dashboard" => [
			"url" => "/",
			"title" => "Dashboard",
			"icon" => "bi bi-speedometer",
			"script_js" => []
		],
		"organigramma" => [
			"separator" => true,
			"title" => "Organigramma",
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
		"professori" => [
			"url" => "/pages/professori",
			"title" => "Professori",
			"icon" => "bi bi-person-badge",
			"script_js" => [
				"/js/pages/professori.js"
			]
		],
		"professori/visualizza" => [
			"url" => "/pages/professori/visualizza",
			"title" => "Visualizza Professore",
			"back_button" => true
		],
		"materie" => [
			"url" => "/pages/materie",
			"title" => "Materie",
			"icon" => "bi bi-backpack",
			"script_js" => [
				"/js/pages/materie.js"
			]
		],
		"materie/visualizza" => [
			"url" => "/pages/materie/visualizza",
			"title" => "Visualizza Materia",
			"back_button" => true
		],
		"gestione-verifiche" => [
			"separator" => true,
			"title" => "Gestione verifiche",
		],
		"verifiche" => [
			"url" => "/pages/verifiche",
			"title" => "Verifiche",
			"icon" => "bi bi-file-earmark-ruled",
			"script_js" => [
				"/js/pages/verifiche.js"
			]
		],
		"verifiche/modifica" => [
			"url" => "/pages/verifiche/modifica",
			"title" => "Modifica Verifica",
			"back_button" => true
		],
	];