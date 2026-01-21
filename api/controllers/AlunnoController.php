<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Classe.php";

class AlunnoController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/alunno/classe/{id}', [$this, 'alunniClasse']);
    }

    public function alunniClasse($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID classe non valido"]);
            return;
        }
        
        if (!Classe::exists($id)) {
            $this->error("`Classe non trovata", 404, "NOT_FOUND");
        }
        
        $classe = new Classe($id);
		$alunni = $classe->getAlunni(true);
		
        echo json_encode($alunni);
    }
}
