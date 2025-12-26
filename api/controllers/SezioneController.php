<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Sezione.php";

class SezioneController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/sezione/{id}', [$this, 'show']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID autore non valido"]);
            return;
        }
        
        if (!Sezione::exists($id)) {
            $this->error("Autore non trovato", 404, "NOT_FOUND");
        }
        
        $sezione = new Sezione($id);
		unset($sezione->verifica);
		
        echo json_encode($sezione);
    }
}
