<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verofalso.php";

class VerofalsoController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/verofalso/{id}', [$this, 'show']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
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
}
