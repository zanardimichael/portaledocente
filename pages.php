<?php
    require_once "./inc/mysql.php";
    global $versione;
    require_once "./inc/utils.php";
    require_once "./inc/class/Utente.php";
	require_once "./inc/class/Professore.php";
	require_once "./inc/class/Message.php";
	require_once "./inc/class/PageHandler.php";
	include "inc/pages.config.php";

    global $categorie_js;
    global $autori_js;
	global $pages;

    $userId = Utente::verifyLogin();
    if (!$userId) {
        header("Location: /login.php");
    }

    /** @var Utente $utente Utente attivo */
    $utente = new Utente($userId, "*");
	$current_prof = Professore::getProfessoreByUtenteID($userId);
	$message = new Message();
	$page = new PageHandler($_GET["req"] ?? false);

    $debug = true;

    $current_page = $_GET["req"] ?? false;
    if (!$current_page) {
        header("Location: /");
    }
	
	if($page->isSafeToProceed()){
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
                    <div class="col-sm-6">
                        <h3 class="mb-0">
                            <?php
                                if ($page->subpage and $page->isBackButtonEnabled()) {
                                    echo '<a class="btn p-1 fs-5" href="/pages/'.$page->previous_page.'"><i class="bi bi-chevron-left"></i> Indietro</a>';
                                } else {
                                    echo $page->title;
                                }
                            ?>
                        </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <?php
                                if ($page->subpage) {
									$breadcrumb_pages = $page->getPagesBreadcrumb();
                                    for($i = 0; $i < count($breadcrumb_pages); $i++) {
										$breadcrumb_page = $breadcrumb_pages[$i];
                                        $subpage = new PageHandler($breadcrumb_page);
										$anchor_start = $i != count($breadcrumb_pages) - 1 ? "<a href=\"/pages/$subpage->current_page\">" : "";
										$anchor_end = $i != count($breadcrumb_pages) - 1 ? "</a>": "";
                                        echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">$anchor_start" . $subpage->title . "$anchor_end</li>";
                                    }
                                } else {
                                    echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">" . $page->title . "</li>";
                                }

                            ?>
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
                    include "page/$page->current_page.page.php";
                    include "modals/$page->current_page.modal.php";
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
<script>
    let createSuccess = false;
    let deleteSuccess = false;
    let updateSuccess = false;
</script>
<?php
		include "inc/script.php";
		
		echo "<script type='text/javascript'> let ID_professore = $current_prof->id; </script>\n";
	
		if(verifyAllGetVars(["createSuccess"])){
			echo "<script> createSuccess = true; </script>";
		}elseif(verifyAllGetVars(["deleteSuccess"])){
			echo "<script> deleteSuccess = true; </script>";
		}elseif(verifyAllGetVars(["updateSuccess"])){
			echo "<script> updateSuccess = true; </script>";
		}
		
		
		
		global $message;
		echo $message->render(true);
	
		if ($page->getPageScripts()) {
			foreach ($page->getPageScripts() as $script) {
				echo "<script src=\"$script?v=$versione\"></script>\n";
			}
		} else {
			echo "<script src=\"/js/pages/$page->current_page.js?v=$versione\"></script>";
		}
	}
	
	echo $page->renderRedirect();
?>
<!--end::Script-->
</body>
<!--end::Body-->
</html>
