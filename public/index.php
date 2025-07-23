<?php
date_default_timezone_set('America/Mexico_City');

// Incluir configuración de errores
require_once __DIR__ . '/../config/error_config.php';

// Iniciar sesión al principio de la aplicación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

// Obtener la URI actual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$config = require __DIR__ . '/../config/config.php';

// Remover el prefijo de la base_url si existe
$baseUrl = $config['base_url'];
if (strpos($uri, $baseUrl) === 0) {
    $uri = substr($uri, strlen($baseUrl));
}

// Asegurarse de que la URI comience con /
if (empty($uri) || $uri === '/') {
    $uri = '/';
}

// Inicializar el router
$router = new Router();
$router->init();

// Despachar la ruta
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
} catch (Exception $e) {
    // Log del error
    error_log($e->getMessage());
    
    // Mostrar un mensaje de error amigable
    http_response_code(500);
    require_once __DIR__ . '/../app/Views/error/500.php';
}

