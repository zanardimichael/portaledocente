<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verifica.php";

class VerificaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/verifica/{id}', [$this, 'show']);
		$router->add('GET', '/sottoverifica/{id}', [$this, 'sottoverifica']);
    }

    public function show($params): void {
	    $this->requireAuth();
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
		$this->requireAuth();
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
