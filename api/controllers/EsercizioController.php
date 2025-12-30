<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Esercizio.php";

class EsercizioController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/esercizio/{id}', [$this, 'show']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
    }

    public function show($params): void {
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
}
