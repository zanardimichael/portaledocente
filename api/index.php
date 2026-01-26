<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load dependencies
require_once $_SERVER["DOCUMENT_ROOT"]."/inc/mysql.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/inc/utils.php";
require_once "Router.php";
require_once "BaseController.php";
global $api;

$api = true;

// Initialize Router
$router = new Router();

// Auto-load controllers from controllers directory
$controllersDir = __DIR__ . '/controllers';
$controllerFiles = glob($controllersDir . '/*Controller.php');

foreach ($controllerFiles as $file) {
    require_once $file;
    $className = basename($file, '.php');
    
    // Instantiate controller and register routes
    if (class_exists($className)) {
        $controller = new $className();
        $controller->registerRoutes($router);
    }
}

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
