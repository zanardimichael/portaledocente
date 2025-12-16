<?php
	require_once "../inc/mysql.php";
	global $versione;
	require_once "../inc/utils.php";
	require_once "../inc/class/Utente.php";

	global $categorie_js;
	global $autori_js;

	$userId = Utente::verifyLogin();
	if (!$userId) {
		header("Location: /admin/login.php");
	}

	/** @var Utente $utente Utente attivo */
	$utente = new Utente($userId, "*");

	$debug = true;

	$current_page = $_GET["req"] ?? false;
	if(!$current_page){
		header("Location: /admin/");
	}
	include "inc/pages.config.php";
	global $pages;

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
					<div class="col-sm-6"><h3 class="mb-0"><?php echo $pages[$current_page]["title"]; ?></h3></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-end">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $pages[$current_page]["title"]; ?></li>
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
					include "list/$current_page.list.php";
					include "modals/$current_page.modal.php";
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
<?php
	include "inc/script.php";

	if($pages[$current_page]["script_js"]){
		foreach ($pages[$current_page]["script_js"] as $script){
			echo "<script src=\"$script?v=$versione\"></script>";
		}
	}
?>
<!--end::Script-->
</body>
<!--end::Body-->
</html>
