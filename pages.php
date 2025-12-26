<?php
    require_once "./inc/mysql.php";
    global $versione;
    require_once "./inc/utils.php";
    require_once "./inc/class/Utente.php";
	require_once "./inc/class/Professore.php";

    global $categorie_js;
    global $autori_js;

    $userId = Utente::verifyLogin();
    if (!$userId) {
        header("Location: /login.php");
    }

    /** @var Utente $utente Utente attivo */
    $utente = new Utente($userId, "*");
	$current_prof = Professore::getProfessoreByUtenteID($userId);

    $debug = true;

    $current_page = $_GET["req"] ?? false;
    if (!$current_page) {
        header("Location: /");
    }
    $current_page_exploded = explode("/", $current_page);
    $subpage = false;
    if (count($current_page_exploded) > 1) {
        $subpage = true;
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
                    <div class="col-sm-6">
                        <h3 class="mb-0">
                            <?php
                                if ($subpage and $pages[$current_page]["back_button"]) {
                                    $precedent_page_exploded = $current_page_exploded;
                                    array_pop($precedent_page_exploded);
                                    echo '<a class="btn p-1 fs-5" href="/pages/'.implode("/", $precedent_page_exploded).'"><i class="bi bi-chevron-left"></i> Indietro</a>';
                                } else {
                                    echo $pages[$current_page]["title"];
                                }
                            ?>
                        </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <?php
                                if ($subpage) {
                                    $incrementing = "";
                                    for ($i = 0; $i < count($current_page_exploded); $i++) {
                                        $page = $current_page_exploded[$i];
                                        $incrementing .= $i == 0 ? $page : "/" . $page;
                                        echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">" . $pages[$incrementing]["title"] . "</li>";
                                    }
                                } else {
                                    echo "<li class=\"breadcrumb-item active\" aria-current=\"page\">" . $pages[$current_page]["title"] . "</li>";
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
                    include "page/$current_page.page.php";
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
<script>
    let createSuccess = false;
    let deleteSuccess = false;
    let updateSuccess = false;
</script>
<?php
    include "inc/script.php";

    if(verifyAllGetVars(["createSuccess"])){
        echo "<script> createSuccess = true; </script>";
    }elseif(verifyAllGetVars(["deleteSuccess"])){
        echo "<script> deleteSuccess = true; </script>";
    }elseif(verifyAllGetVars(["updateSuccess"])){
        echo "<script> updateSuccess = true; </script>";
    }

    if ($pages[$current_page]["script_js"]) {
        foreach ($pages[$current_page]["script_js"] as $script) {
            echo "<script src=\"$script?v=$versione\"></script>";
        }
    } else {
        echo "<script src=\"/js/pages/$current_page.js?v=$versione\"></script>";
    }
?>
<!--end::Script-->
</body>
<!--end::Body-->
</html>
