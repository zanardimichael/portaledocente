<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Rispostamultipla.php";

class RispostaMultiplaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/rispostamultipla/{id}', [$this, 'show']);
		$router->add('GET', '/rispostamultipla/risposta/{id}', [$this, 'getRisposta']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID risposta multipla non valido"]);
            return;
        }
        
        if (!Rispostamultipla::exists($id)) {
            $this->error("Risposta multipla non trovata", 404, "NOT_FOUND");
        }
        
        $rispostamultipla = new Rispostamultipla($id);
		unset($rispostamultipla->verifica);
		
        echo json_encode($rispostamultipla);
    }
	
	public function getRisposta($params): void {
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID risposta non valido"]);
			return;
		}
		
		if (!Rispostamultipla::existsRisposta($id)) {
			$this->error("Risposta non trovata", 404, "NOT_FOUND");
		}
		
		echo json_encode(Rispostamultipla::getRisposta($id));
	}
}
