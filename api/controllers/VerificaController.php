<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Verifica.php";

class VerificaController extends BaseController {
    
    public function registerRoutes(Router $router) {
	    $router->add('GET', '/verifica/{id}', [$this, 'show']);
		$router->add('GET', '/sottoverifica/{id}', [$this, 'sottoverifica']);
		$router->add('GET', '/verifica/{id}/correzioni', [$this, 'correzioni']);
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
	
	public function correzioni($params): void {
		global $mysql;
		$this->requireAuth();
		$id = $params['id'] ?? null;
		
		if(!is_numeric($id)){
			$this->error("ID verifica non valido", 400);
		}
		
		if (!Verifica::exists($id)) {
			$this->error("Verifica non trovata", 404, "NOT_FOUND");
		}
		
		$id = $mysql->connection->real_escape_string($id);
		
		$sql = "
			SELECT c.ID, c.data_verifica, v.titolo, c.note, c.analisi_ai, v.id as id_verifica, v.ID_verifica as parent_verifica
			FROM correzione c
			JOIN verifica v ON c.ID_verifica = v.ID
			WHERE (v.ID = '$id' OR v.ID_verifica = '$id')
			ORDER BY c.data_verifica DESC
		";
		
		$result = $mysql->query($sql);
		$correzioni = [];
		while($row = mysqli_fetch_assoc($result)) {
			$correzioni[] = [
				'id' => $row['ID'],
				'data' => date("d/m/Y", strtotime($row['data_verifica'])),
				'titolo' => $row['titolo'],
				'note' => $row['note'],
				'analisi_ai' => $row['analisi_ai'],
				'id_verifica' => $row['id_verifica'],
				'is_sottoverifica' => $row['parent_verifica'] != null
			];
		}
		
		$this->json(['success' => true, 'data' => $correzioni]);
	}
}
