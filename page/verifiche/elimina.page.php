<?php
	
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/class/Verifica.php";
	
	global $page;
	
	$id = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST' and verifyAllPostVars(["id"])) {
		
		if (Verifica::delete($_POST["id"])) {
			$page->setRedirect("/pages/verifiche?deleteSuccess")
				->setUnsafe(UnsafeReasons::Redirect);
		} else {
			$page->message->setMessage("Errore nell'eliminazione della verifica")
				->setMessageType(MessageType::Error)
				->show();
		}
	} elseif (verifyAllGetVars(["id"])) {
		$id = $_GET["id"];
	} else {
		$page->setRedirect("/pages/verifiche")
			->setUnsafe(UnsafeReasons::Redirect);
	}
	
	if($page->isSafeToProceed()){
		$verifica = new Verifica($id);
?>
<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Eliminazione Verifica <?php echo $verifica->titolo; ?></div></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $verifica->id; ?>">
				<div class="card-body">
					Sei sicuro di voler eliminare la verifica <?php echo $verifica->titolo; ?><br/>
					Qualsiasi domanda al suo interno verrà rimossa definitivamente.
				</div>
				<div class="card-footer">
					<button class="btn btn-danger" type="submit">Elimina</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
	}
?>