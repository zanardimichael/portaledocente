<?php
	
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/class/Classe.php";
	
	$id = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST' and verifyAllPostVars(["id"])) {
		
		if (Classe::delete($_POST["id"])) {
			echo "<script> location.href = \"/pages/classi?deleteSuccess\"</script>";
		} else {
			echo '<script>
                    document.addEventListener("DOMContentLoaded", () => {
                        Toastify({
                            text: "Errore eliminazione",
                            duration: 3000
                            style: ["background": "red"]
                        }).showToast();
                    })
                </script>';
		}
	} elseif (verifyAllGetVars(["id"])) {
		$id = $_GET["id"];
	} else {
		echo "<script> location.href = \"/pages/classi\"</script>";
		exit();
	}
	$classe = new Classe($id);
?>
<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Eliminazione Classe <?php echo $classe->classe.$classe->sezione; ?></div></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $classe->id; ?>">
				<div class="card-body">
					Sei sicuro di voler eliminare la classe <?php echo $classe->classe.$classe->sezione; ?>
				</div>
				<div class="card-footer">
					<button class="btn btn-danger" type="submit">Elimina</button>
				</div>
			</form>
		</div>
	</div>
</div>
