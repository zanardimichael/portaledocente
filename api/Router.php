<?php

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch($method, $uri) {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remove /api prefix if present
        if (strpos($uri, '/api') === 0) {
            $uri = substr($uri, 4);
        }
        
        // Remove trailing slash
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route['path']);
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove integer keys from matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Call the callback with params
                if (is_array($route['callback'])) {
                    // Controller method: [$controller, 'method']
                    return call_user_func($route['callback'], $params);
                } else {
                    // Closure
                    return call_user_func($route['callback'], $params);
                }
            }
        }

        $this->sendNotFound();
    }

    private function convertPathToRegex($path) {
        // Convert {param} to (?P<param>[^/]+)
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        // Add start and end delimiters
        return '#^' . $pattern . '$#';
    }

    private function sendNotFound() {
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['error' => 'Endpoint non trovato']);
        exit;
    }
}
