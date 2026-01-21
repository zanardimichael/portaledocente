<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/inc/class/RispostaMultipla.php";

class RispostaMultiplaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/rispostamultipla/{id}', [$this, 'show']);
		$router->add('GET', '/rispostamultipla/risposta/{id}', [$this, 'getRisposta']);
	    $router->add('PATCH', '/rispostamultipla/correzione/{id}', [$this, 'correzione']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID risposta multipla non valido"]);
            return;
        }
        
        if (!RispostaMultipla::exists($id)) {
            $this->error("Risposta multipla non trovata", 404, "NOT_FOUND");
        }
        
        $rispostamultipla = new RispostaMultipla($id);
		
        echo json_encode($rispostamultipla);
    }
	
	public function getRisposta($params): void {
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID risposta non valido"]);
			return;
		}
		
		if (!RispostaMultipla::existsRisposta($id)) {
			$this->error("Risposta non trovata", 404, "NOT_FOUND");
		}
		
		echo json_encode(RispostaMultipla::getRisposta($id));
	}
	
	public function correzione($params): void {
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID correzione non valido"]);
			return;
		}
		
		if (!CorrezioneDomanda::exists($id)) {
			$this->error("Correzione risposta multipla non trovata", 404, "NOT_FOUND");
		}
		$_PATCH = [];
		parse_str(file_get_contents('php://input'), $_PATCH);
		
		CorrezioneDomanda::edit($id, [
			"valore" => implode(",", $_PATCH["risposta_multipla"] ?? []),
			"parziale" => $_PATCH["parziale"] ?? 0,
			"punteggio" => $_PATCH["punteggio"] ?? 0,
		]);
		
		$correzione_domanda = new CorrezioneDomanda($id);
		
		echo json_encode(["punteggio" => $correzione_domanda->getPunteggio()]);
	}
}
