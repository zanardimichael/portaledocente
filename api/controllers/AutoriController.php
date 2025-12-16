<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Autore.php";

class AutoriController extends BaseController {
    
    public function registerRoutes(Router $router) {
        $router->add('GET', '/autori', [$this, 'index']);
        $router->add('GET', '/autori/{id}', [$this, 'show']);
        $router->add('POST', '/autori', [$this, 'create']);
        $router->add('PUT', '/autori/{id}', [$this, 'update']);
        $router->add('PATCH', '/autori/{id}', [$this, 'patch']);
        $router->add('DELETE', '/autori/{id}', [$this, 'delete']);
    }
    
    public function create() {
        $this->requireAuth();
        $data = $this->getJsonInput();
        
        $this->validateRequired($data, ['nome']);
        
        $newData = [
            'nome' => $data['nome'],
            'cognome' => $data['cognome'] ?? '',
            'biografia' => $data['biografia'] ?? ''
        ];
        
        Autore::createData($newData);
        
        $this->json(['message' => 'Autore creato con successo'], 201);
    }

    public function update($params) {
        $this->requireAuth();
        $id = $params['id'];
        $data = $this->getJsonInput();
        
        if (!Autore::exists($id)) {
            $this->error("Autore non trovato", 404, "NOT_FOUND");
        }
        $autore = new Autore($id, "id");
        
        $updateData = [
            'id' => $id,
            'nome' => $data['nome'] ?? '',
            'cognome' => $data['cognome'] ?? '',
            'biografia' => $data['biografia'] ?? ''
        ];
        
        if (empty($updateData['nome'])) {
             $this->error("Campo obbligatorio mancante per PUT: nome", 400, "VALIDATION_ERROR");
        }

        Autore::saveData($updateData);
        
        $this->json(['message' => 'Autore aggiornato con successo']);
    }

    public function patch($params) {
        $this->requireAuth();
        $id = $params['id'];
        $data = $this->getJsonInput();
        
        if (!Autore::exists($id)) {
            $this->error("Autore non trovato", 404, "NOT_FOUND");
        }
        $autore = new Autore($id, "*");
        
        $updateData = [
            'id' => $id,
            'nome' => $data['nome'] ?? $autore->nome,
            'cognome' => $data['cognome'] ?? $autore->cognome,
            'biografia' => $data['biografia'] ?? $autore->biografia
        ];
        
        Autore::saveData($updateData);
        
        $this->json(['message' => 'Autore aggiornato con successo']);
    }

    public function delete($params) {
        $this->requireAuth();
        $id = $params['id'];
        
        if (!Autore::exists($id)) {
            $this->error("Autore non trovato", 404, "NOT_FOUND");
        }
        
        $autore = new Autore($id, "id");
        
        // Check if author has songs
        $count = Autore::getCanzoniCountByAutore($id);
        if ($count > 0) {
            $this->error("Impossibile eliminare l'autore: ci sono $count canzoni associate", 409, "CONFLICT");
        }
        
        Autore::deleteData($id);
        
        http_response_code(204);
        exit;
    }
    
    public function index($params = []) {
        $search = [];
        
        // Filtro per ricerca
        if(isset($_GET["search"]) && $_GET["search"] != ""){
            global $connection;
            $searchTerm = mysqli_escape_string($connection, $_GET["search"]);
            $search["nome"] = $searchTerm;
            $search["cognome"] = $searchTerm;
        }
        
        $autori = Autore::getAll("*", $search);
        
        echo json_encode([
            "data" => $autori,
            "total" => count($autori)
        ]);
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        
        if(!is_numeric($id)){
            http_response_code(400);
            echo json_encode(["error" => "ID autore non valido"]);
            return;
        }
        
        if (!Autore::exists($id)) {
            $this->error("Autore non trovato", 404, "NOT_FOUND");
        }
        
        $autore = new Autore($id, "*");

        $autore->canzoniCount = Autore::getCanzoniCountByAutore($id);
        
        echo json_encode($autore);
    }
}
