<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verifica.php";

class VerificaController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/verifica/{id}', [$this, 'show']);
		$router->add('GET', '/sottoverifica/{id}', [$this, 'sottoverifica']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID verifica non valido"]);
            return;
        }
        
        if (!Verifica::exists($id)) {
            $this->error("`Verifica non trovata", 404, "NOT_FOUND");
        }
        
        $verifica = new Verifica($id);
		
        echo json_encode($verifica);
    }
	
	public function sottoverifica($params): void {
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			http_response_code(400);
			echo json_encode(["error" => "ID verifica non valido"]);
			return;
		}
		
		if (!Verifica::exists($id)) {
			$this->error("`Verifica non trovata", 404, "NOT_FOUND");
		}
		
		$verifica = new Verifica($id);
		
		echo json_encode($verifica->getSottoverifiche());
	}
}
