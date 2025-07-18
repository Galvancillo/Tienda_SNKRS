<?php
// Configuración para manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Crear directorio de logs si no existe
if (!file_exists(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Función para manejar errores fatales
function handleFatalError() {
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR) {
        error_log("Fatal Error: " . $error['message'] . " in " . $error['file'] . " on line " . $error['line']);
        
        // Si es una petición AJAX, devolver JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error interno del servidor'
            ]);
        }
    }
}

// Registrar la función para manejar errores fatales
register_shutdown_function('handleFatalError'); 