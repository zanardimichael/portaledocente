<?php
	require_once "inc/mysql.php";
	require_once "inc/utils.php";
	require_once "inc/class/Utente.php";

	global $categorie_js;
	global $autori_js;

	$userId = Utente::verifyLogin();
	if (!$userId) {
		header("Location: /login.php");
	}

	/** @var Utente $utente Utente attivo */
	$utente = new Utente($userId, "*");

	$debug = true;

	include "inc/pages.config.php";
	$current_page = false;

?>
<!doctype html>
<html lang="it">
<!--begin::Head-->
<?php include "inc/head.php" ?>
<!--end::Head-->
<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<!--begin::App Wrapper-->
<div class="app-wrapper">
	<!--begin::Header-->
	<?php include "inc/header.php" ?>
	<!--end::Header-->
	<!--begin::Sidebar-->
	<?php include "inc/sidebar.php" ?>
	<!--end::Sidebar-->
	<!--begin::App Main-->
	<main class="app-main">
		<!--begin::App Content Header-->
		<div class="app-content-header">
			<!--begin::Container-->
			<div class="container-fluid">
				<!--begin::Row-->
				<div class="row">
					<div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-end">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
						</ol>
					</div>
				</div>
				<!--end::Row-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::App Content Header-->
		<!--begin::App Content-->
		<div class="app-content">
			<!--begin::Container-->
			<div class="container-fluid">
				<?php
					include "list/dashboard.php";
				?>
			</div>
			<!--end::Container-->
		</div>
		<!--end::App Content-->
	</main>
	<!--end::App Main-->
	<!--begin::Footer-->
	<?php include "inc/footer.php" ?>
	<!--end::Footer-->
</div>
<!--end::App Wrapper-->
<!--begin::Script-->
<?php include "inc/script.php" ?>
<!--end::Script-->
</body>
<!--end::Body-->
</html>
