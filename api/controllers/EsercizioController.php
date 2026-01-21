<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Esercizio.php";

class EsercizioController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/esercizio/{id}', [$this, 'show']);
	    $router->add('PATCH', '/esercizio/correzione/{id}', [$this, 'correzione']);
    }

    public function show($params): void {
	    $this->requireAuth();
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID esercizio non valido"]);
            return;
        }
        
        if (!Esercizio::exists($id)) {
            $this->error("Esercizio non trovato", 404, "NOT_FOUND");
        }
        
        $esercizio = new Esercizio($id);
		unset($esercizio->verifica);
		
        echo json_encode($esercizio);
    }
	
	public function correzione($params): void {
		$this->requireAuth();
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID correzione non valido"]);
			return;
		}
		
		if (!CorrezioneDomanda::exists($id)) {
			$this->error("Correzione esercizio non trovata", 404, "NOT_FOUND");
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
