<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/inc/class/RispostaAperta.php";

class RispostaApertaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/rispostaaperta/{id}', [$this, 'show']);
	    $router->add('PATCH', '/rispostaaperta/correzione/{id}', [$this, 'correzione']);
    }

    public function show($params): void {
	    $this->requireAuth();
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID risposta aperta non valido"]);
            return;
        }
        
        if (!Verofalso::exists($id)) {
            $this->error("Risposta aperta non trovata", 404, "NOT_FOUND");
        }
        
        $rispostaaperta = new RispostaAperta($id);
		unset($rispostaaperta->verifica);
		
        echo json_encode($rispostaaperta);
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
			$this->error("Correzione risposta aperta non trovata", 404, "NOT_FOUND");
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
