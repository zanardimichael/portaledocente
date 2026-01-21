<?php
	
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/utils.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/inc/class/Correzione.php";
	
	global $page;
	
	$id = null;
	if ($_SERVER['REQUEST_METHOD'] == 'POST' and verifyAllPostVars(["id"])) {
		
		if (Correzione::delete($_POST["id"])) {
			$page->setRedirect("/pages/correzioni?deleteSuccess")
				->setUnsafe(UnsafeReasons::Redirect);
		} else {
			$page->message->setMessage("Errore nell'eliminazione della correzione")
				->setMessageType(MessageType::Error)
				->show();
		}
	} elseif (verifyAllGetVars(["id"])) {
		$id = $_GET["id"];
	} else {
		$page->setRedirect("/pages/correzioni")
			->setUnsafe(UnsafeReasons::Redirect);
	}
	
	if($page->isSafeToProceed()){
		$correzione = new Correzione($id);
?>
<div class="row">
	<div class="col-12">
		<div class="card card-info card-outline mb-4">
			<div class="card-header"><div class="card-title">Eliminazione Correzione <b><?php echo $correzione->verifica->titolo; ?></b></div></div>
			<form action="" method="post">
				<input type="hidden" name="id" value="<?php echo $correzione->id; ?>">
				<div class="card-body">
					Sei sicuro di voler eliminare la correzione della verifica <b><?php echo $correzione->verifica->titolo; ?></b><br/>
					Qualsiasi correzione al suo interno verrà rimossa definitivamente.
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