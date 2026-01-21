<?php
	global $page;
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/CorrezioneDomanda.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/Alunno.php';
	
	if($page->getRequestMethod() == 'POST') {
		if(verifyAllPostVars(["id", "alunni"])){
			$id = $_POST["id"];
			$alunni = explode(",", $_POST["alunni"]);
			$correzione = new Correzione($id);
			if($correzione->addAlunni($alunni)){
				$page->message->setMessageType(MessageType::Success)->setMessage("Alunno/i aggiunto/i con successo")->show();
			}else{
				$page->message->setMessageType(MessageType::Error)->setMessage("Errore nell'inserimento")->show();
			}
			$page->setGlobalVariable("ID_correzione", $correzione->id);
			$page->setJavascriptVariable("ID_correzione", $correzione->id);
		}
		if(!verifyAllPostVars(["id"])){
			$page->setRedirect("/")->setUnsafe(UnsafeReasons::Redirect);
		}
	}else{
		if(verifyAllGetVars(["id"])){
			$page->setGlobalVariable("ID_correzione", $_GET["id"]);
			$page->setJavascriptVariable("ID_correzione", $_GET["id"]);
			
			if(!Correzione::exists($page->getGlobalVariable("ID_correzione"))){
				$page->setRedirect("/")->setUnsafe(UnsafeReasons::Redirect);
			}
		}else{
			$page->setRedirect("/")->setUnsafe(UnsafeReasons::Redirect);
		}
	}
	
	if(verifyAllGetVars(["createSuccess"])){
		$page->message->setMessage("Correzione creata con successo, inserisci gli alunni della verifica")->setMessageType(MessageType::Success)->show();
	}
	
	if($page->isSafeToProceed()) {
		$correzione = new Correzione($page->getGlobalVariable("ID_correzione"));
		$alunni = $correzione->getAlunni();
		$punteggio_verifica = $correzione->verifica->getPunteggioVerifica();
?>


<div class="row">
	<table class="table table-striped" id="alunni-table" style="max-height: 1000px;">
		<thead>
		<tr><th>Nome</th><th>Voto</th><th>Punteggio</th><th style="width: 15%;">Azioni</th></tr>
		</thead>
		<tbody>
		<?php
			
			foreach($alunni as $alunno){
				$voto = $correzione->getVotoAlunno($alunno->id);
				$colore_voto = "text-success";
				if($voto < 6){
					$colore_voto = "text-danger";
				}
				if($voto == 0){
					$voto = "--";
				}
				?>
				<tr>
					<td><?php echo $alunno->getNomeCognome(); ?></td>
					<td class="fs-5 <?php echo $colore_voto;?>"><?php echo $voto; ?></td>
					<td><?php echo $correzione->getPunteggioAlunno($alunno->id)."/".$punteggio_verifica; ?></td>
					<td>
						<a class="btn btn-primary" href="/pages/correzioni/correzione?id=<?php echo $correzione->id."&alunno=".$alunno->id; ?>">Correggi</a>
					</td>
				</tr>
				<?php
			}
		?>
		</tbody>
	</table>
</div>

<?php
	}
?>