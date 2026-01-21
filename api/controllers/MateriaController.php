<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Materia.php";

class MateriaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/materia/{id}', [$this, 'show']);
	    $router->add('GET', '/materia/{id_classe}/{id_professore}', [$this, 'classiProfessori']);
    }

    public function show($params): void {
	    $this->requireAuth();
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID materia non valido"]);
            return;
        }
        
        if (!Materia::exists($id)) {
            $this->error("Materia non trovata", 404, "NOT_FOUND");
        }
        
        $materia = new Materia($id);
		
        echo json_encode($materia);
    }
	
	public function classiProfessori($params): void {
		$this->requireAuth();
		$id_classe = $params['id_classe'] ?? null;
		$id_professore = $params['id_professore'] ?? null;
		
		if(!is_numeric($id_classe)){
			http_response_code(400);
			echo json_encode(["error" => "ID classe non valido"]);
			return;
		}
		
		if(!is_numeric($id_professore)){
			http_response_code(400);
			echo json_encode(["error" => "ID professore non valido"]);
			return;
		}
		
		$array = Materia::getMaterieClasseProfessore($id_classe, $id_professore);
		echo json_encode($array);
	}
}
