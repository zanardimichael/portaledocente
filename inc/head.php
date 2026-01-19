<?php

	global $pages;
	global $current_page;
	if(!$current_page) $current_page = "dashboard";
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $pages[$current_page]["title"]; ?> | Portale Docente</title>
	<!--begin::Accessibility Meta Tags-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)"/>
	<!--end::Accessibility Meta Tags-->
	<!--begin::Primary Meta Tags-->
	<meta name="title" content="<?php echo $pages[$current_page]["title"]; ?> | Portale Docente"/>
	<!--end::Primary Meta Tags-->
	<!--begin::Accessibility Features-->
	<!-- Skip links will be dynamically added by accessibility.js -->
	<meta name="supported-color-schemes" content="light dark"/>
	<link rel="preload" href="/css/adminlte.css" as="style"/>
	<!--end::Accessibility Features-->
	<!--begin::Fonts-->
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
		integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
		crossorigin="anonymous"
		media="print"
		onload="this.media='all'"
	/>
	<!--end::Fonts-->
	<!--begin::Third Party Plugin(OverlayScrollbars)-->
	<link
		rel="stylesheet"
		href="/css/overlayscrollbars.min.css"
		crossorigin="anonymous"
	/>
	<!--end::Third Party Plugin(OverlayScrollbars)-->
	<!--begin::Third Party Plugin(Bootstrap Icons)-->
	<link
		rel="stylesheet"
		href="/css/bootstrap-icons.min.css"
		crossorigin="anonymous"
	/>
	<!--end::Third Party Plugin(Bootstrap Icons)-->
	<!--begin::Required Plugin(AdminLTE)-->
	<link rel="stylesheet" href="/css/adminlte.css"/>
	<!--end::Required Plugin(AdminLTE)-->

	<link href="/css/datatables/dataTables.dataTables.min.css" rel="stylesheet">
	<link href="/css/datatables/rowReorder.dataTables.min.css" rel="stylesheet">
	<link href="/css/datatables/buttons.dataTables.min.css" rel="stylesheet">
	<link href="/css/datatables/select.dataTables.min.css" rel="stylesheet">
	<link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/toastify.min.css" rel="stylesheet">
	<link href="/css/summernote-bs5.min.css" rel="stylesheet">
	<link href="/css/override.css" rel="stylesheet"/>
</head>