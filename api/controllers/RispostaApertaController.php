<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/inc/class/RispostaAperta.php";

class RispostaApertaController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/rispostaaperta/{id}', [$this, 'show']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
    }

    public function show($params): void {
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
}
