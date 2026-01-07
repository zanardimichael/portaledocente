<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Sezione.php";

class SezioneController extends BaseController {
    
    public function registerRoutes(Router $router) {
//        $router->add('GET', '/sezione', [$this, 'index']);
	    $router->add('GET', '/sezione/{id}', [$this, 'show']);
	    $router->add('PATCH', '/sezione/{id_sezione}/invertiordine', [$this, 'invertiOrdine']);
	    $router->add('PATCH', '/sezione/{id_sezione}/invertiOrdineEsercizio/{ordine_esercizio}', [$this, 'invertiOrdineEsercizio']);
//        $router->add('POST', '/sezione', [$this, 'create']);
//        $router->add('PUT', '/sezione/{id}', [$this, 'update']);
//        $router->add('PATCH', '/sezione/{id}', [$this, 'patch']);
//        $router->add('DELETE', '/sezione/{id}', [$this, 'delete']);
    }

    public function show($params): void {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID sezione non valido"]);
            return;
        }
        
        if (!Sezione::exists($id)) {
            $this->error("Sezione non trovata", 404, "NOT_FOUND");
        }
        
        $sezione = new Sezione($id);
		unset($sezione->verifica);
		
        echo json_encode($sezione);
    }
	
	public function invertiOrdine($params): void {
		$id_sezione = $params['id_sezione'] ?? null;
		
		if(!is_numeric($id_sezione)){
			http_response_code(400);
			echo json_encode(["error" => "ID sezione non valido"]);
			return;
		}
		
		$sezione = new Sezione($id_sezione, ["ordine"]);
		$response = array("result" => $sezione->invertiOrdineConSuccessivo());
		
		echo json_encode($response);
	}
	
	public function invertiOrdineEsercizio($params): void {
		$id_sezione = $params['id_sezione'] ?? null;
		$ordine_esercizio = $params['ordine_esercizio'] ?? null;
		
		if(!is_numeric($id_sezione)){
			http_response_code(400);
			echo json_encode(["error" => "ID sezione non valido"]);
			return;
		}
		
		if(!is_numeric($ordine_esercizio)){
			http_response_code(400);
			echo json_encode(["error" => "Ordine esercizio non valido"]);
			return;
		}
		
		$sezione = new Sezione($id_sezione);
		$response = array("result" => $sezione->invertiOrdineEsercizioConSuccessivo($ordine_esercizio));
		
		echo json_encode($response);
	}
}
