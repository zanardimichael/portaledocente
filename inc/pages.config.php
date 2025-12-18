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
		]
	];