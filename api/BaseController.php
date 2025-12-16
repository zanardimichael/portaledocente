<?php

abstract class BaseController {
    
    /**
     * Register routes for this controller
     * Must be implemented by child classes
     */
    abstract public function registerRoutes(Router $router);
    
    /**
     * Send JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Verify authentication
     * Returns user ID if authenticated, otherwise sends 401 error
     */
    protected function requireAuth() {
        require_once $_SERVER["DOCUMENT_ROOT"]."/inc/class/Utente.php";
        
        $userId = Utente::verifyLogin();
        
        if (!$userId) {
            $this->error("Non autorizzato", 401, "UNAUTHORIZED");
        }
        
        return $userId;
    }

    /**
     * Get JSON input data
     */
    protected function getJsonInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("JSON non valido", 400, "VALIDATION_ERROR");
        }
        return $input;
    }

    /**
     * Validate required fields
     */
    protected function validateRequired($data, $fields) {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            $this->error("Campi obbligatori mancanti", 400, "VALIDATION_ERROR", $missing);
        }
    }

    /**
     * Send error response
     */
    protected function error($message, $statusCode = 400, $code = "GENERIC_ERROR", $details = null) {
        $response = [
            'error' => $code,
            'message' => $message
        ];
        
        if ($details !== null) {
            $response['details'] = $details;
        }
        
        $this->json($response, $statusCode);
    }
}
