<?php
	global $page;
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/inc/class/CorrezioneDomanda.php';
	
	if(verifyAllGetVars(["id", "alunno"])){
		$page->setGlobalVariable("ID_alunno", $_GET["alunno"]);
		$page->setGlobalVariable("ID_correzione", $_GET["id"]);
		$page->setJavascriptVariable("ID_correzione", $_GET["id"]);
		
		if(!Correzione::exists($page->getGlobalVariable("ID_correzione"))){
			$page->setRedirect("/")->setUnsafe(UnsafeReasons::Redirect);
		}
	}else{
		$page->setRedirect("/")->setUnsafe(UnsafeReasons::Redirect);
	}
	
if($page->isSafeToProceed()) {
	
	$correzione = new Correzione($page->getGlobalVariable("ID_correzione"));
	$verifica = $correzione->verifica;
	$alunno = new Alunno($page->getGlobalVariable("ID_alunno"));
	
	$prossimo_alunno = $alunno->getNextAlunno();
	$alunno_precedente = $alunno->getPreviousAlunno();
	
	$prossimo_alunno_attivo = !$prossimo_alunno ? "disabled" : "";
	$alunno_precedente_attivo = !$alunno_precedente ? "disabled" : "";
	
	$prossimo_alunno_url = $prossimo_alunno ? "href=\"?id=".$page->getGlobalVariable("ID_correzione")."&alunno=".$prossimo_alunno->id."\"": "";
	$alunno_precedente_url = $alunno_precedente ? "href=\"?id=".$page->getGlobalVariable("ID_correzione")."&alunno=".$alunno_precedente->id."\"": "";
?>


	<div class="card mb-2">
		<div class="card-header">
			Correzione Verifica - <b><?php echo $alunno->getNomeCognome(); ?></b>
		</div>
	</div>
	<div class="mb-2">
		<a <?php echo $alunno_precedente_url; ?>>
			<button class="btn btn-light p-1 fs-6" style="background-color: var(--bs-gray-300);" <?php echo $alunno_precedente_attivo; ?>>
				<i class="bi bi-chevron-left"></i>Alunno precedente
			</button>
		</a>
		<a <?php echo $prossimo_alunno_url; ?>>
			<button class="btn btn-light p-1 fs-6 float-end" style="background-color: var(--bs-gray-300);" <?php echo $prossimo_alunno_attivo; ?>>
				Alunno successivo<i class="bi bi-chevron-right"></i>
			</button>
		</a>
	</div>
<?php
	$sezioni = $verifica->getSezioni();
	$punteggio_finale = 0;
	
	foreach($sezioni as $sezione){
		$domande = $sezione->getDomande();
		
		?>
		<div class="col-12 sezione" id-sezione="<?php echo $sezione->id; ?>" ordine="<?php echo $sezione->ordine; ?>">
			<div class="card card-info card-outline mb-4">
				<div class="card-header">
					<div class="card-title">
						<?php echo $sezione->titolo; ?>
					</div>
				</div>
				<div class="card-body">
				<?php
				
					foreach($domande["domande"] as $domanda){
						$correzione_domanda = CorrezioneDomanda::getCorrezioneDomandaFromEsercizio($domanda, $page->getGlobalVariable("ID_alunno"));
						
						$punteggio_finale += $correzione_domanda->getPunteggio();
						echo $correzione_domanda->render();
					}
				
				?>
				</div>
			</div>
		</div>
		<?php
	}
	
	$punteggio_verifica = $verifica->getPunteggioVerifica();
	$voto = floor($punteggio_finale / $punteggio_verifica * 20) / 2;
	
	$colore_voto = "text-success";
	if($voto < 6){
		$colore_voto = "text-danger";
	}
?>
	
	<div class="card">
		<div class="card-header">
			<div class="card-title">
				Voto: <span class="<?php echo $colore_voto; ?>"><?php echo $voto; ?></span>
			</div>
			<div class="float-end">Punteggio: <?php echo $punteggio_finale."/".$punteggio_verifica; ?></div>
		</div>
	</div>

<?php
	}
?>