<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verofalso.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/CorrezioneDomanda.php";

class VerofalsoController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/verofalso/{id}', [$this, 'show']);
		$router->add('PATCH', '/verofalso/correzione/{id}', [$this, 'correzione']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID verofalso non valido"]);
            return;
        }
        
        if (!Verofalso::exists($id)) {
            $this->error("Verofalso non trovato", 404, "NOT_FOUND");
        }
        
        $verofalso = new Verofalso($id);
		unset($verofalso->verifica);
		
        echo json_encode($verofalso);
    }
	
	public function correzione($params): void {
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID correzione non valido"]);
			return;
		}
		
		if (!CorrezioneDomanda::exists($id)) {
			$this->error("Correzione verofalso non trovata", 404, "NOT_FOUND");
		}
		$_PATCH = [];
		parse_str(file_get_contents('php://input'), $_PATCH);
		
		CorrezioneDomanda::edit($id, [
			"valore" => $_PATCH["valore"] ?? "",
			"parziale" => $_PATCH["parziale"] ?? 0,
			"punteggio" => $_PATCH["punteggio"] ?? 0,
		]);
		
		$correzione_domanda = new CorrezioneDomanda($id);
		
		echo json_encode(["punteggio" => $correzione_domanda->getPunteggio()]);
	}
}
