<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $baseUrl;

    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->baseUrl = $config['base_url'];
    }

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($method, $uri) {
        // Eliminar query strings
        $uri = explode('?', $uri)[0];
        
        // Remover el prefijo de la base_url si existe
        if (strpos($uri, $this->baseUrl) === 0) {
            $uri = substr($uri, strlen($this->baseUrl));
        }

        // Asegurarse de que la URI comience con /
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri, $params)) {
                $controller = new $route['controller']();
                $action = $route['action'];
                
                // Pasar parámetros al método del controlador
                if (!empty($params)) {
                    return call_user_func_array([$controller, $action], $params);
                } else {
                    return $controller->$action();
                }
            }
        }
        
        // Si no se encuentra la ruta, mostrar error 404
        header("HTTP/1.0 404 Not Found");
        if (file_exists(__DIR__ . '/../Views/404.php')) {
            require_once __DIR__ . '/../Views/404.php';
        } else {
            echo "404 - Página no encontrada";
        }
    }

    private function matchPath($routePath, $uri, &$params = []) {
        // Extraer nombres de parámetros de la ruta
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        $paramNames = $paramNames[1];
        
        // Convertir parámetros de ruta en expresiones regulares
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        if (preg_match($pattern, $uri, $matches)) {
            // Extraer los valores de los parámetros
            $params = array_slice($matches, 1);
            return true;
        }
        
        return false;
    }

    public function init() {
        // Rutas públicas
        $this->add('GET', '/', 'App\Controllers\HomeController', 'index');
        $this->add('GET', '/login', 'App\Controllers\AuthController', 'showLogin');
        $this->add('POST', '/login', 'App\Controllers\AuthController', 'login');
        $this->add('GET', '/logout', 'App\Controllers\AuthController', 'logout');
        $this->add('POST', '/register', 'App\Controllers\AuthController', 'register');

        // Rutas de productos públicas
        $this->add('GET', '/productos/hombre', 'App\Controllers\ProductoController', 'hombre');
        $this->add('GET', '/productos/mujer', 'App\Controllers\ProductoController', 'mujer');
        $this->add('GET', '/productos/ninos', 'App\Controllers\ProductoController', 'ninos');
        $this->add('GET', '/productos/ofertas', 'App\Controllers\ProductoController', 'ofertas');
        $this->add('GET', '/productos/snkrs', 'App\Controllers\ProductoController', 'snkrs');
        $this->add('GET', '/productos/nuevo', 'App\Controllers\ProductoController', 'nuevo');
        $this->add('GET', '/productos/carrito', 'App\\Controllers\\CarritoController', 'ver');
        $this->add('POST', '/productos/carrito', 'App\\Controllers\\CarritoController', 'ver');
        $this->add('GET', '/productos/detalle/{id}', 'App\\Controllers\\ProductoController', 'detalleProducto');
        $this->add('POST', '/productos/agregar-al-carrito', 'App\\Controllers\\ProductoController', 'agregarAlCarrito');
        // Ruta para agregar al carrito
        $this->add('POST', '/carrito/agregar', 'App\\Controllers\\CarritoController', 'agregar');

        // Rutas del panel de administrador
        $this->add('GET', '/admin', 'App\Controllers\AdminController', 'index');
        $this->add('GET', '/admin/productos', 'App\Controllers\AdminController', 'productos');
        $this->add('GET', '/admin/usuarios', 'App\Controllers\AdminController', 'usuarios');
        $this->add('GET', '/admin/pedidos', 'App\Controllers\AdminController', 'pedidos');
        $this->add('GET', '/admin/productos/tallas', 'App\\Controllers\\ProductoController', 'obtenerTallas');
        $this->add('GET', '/admin/productos/categorias', 'App\\Controllers\\ProductoController', 'obtenerCategorias');

        // Rutas de API para productos
        $this->add('GET', '/admin/productos/obtener/{id}', 'App\Controllers\ProductoController', 'obtenerProducto');
        $this->add('POST', '/admin/productos/crear', 'App\Controllers\ProductoController', 'crearProducto');
        $this->add('POST', '/admin/productos/editar/{id}', 'App\Controllers\ProductoController', 'editarProducto');
        $this->add('POST', '/admin/productos/eliminar/{id}', 'App\Controllers\ProductoController', 'eliminarProducto');
        $this->add('POST', '/admin/productos/reducir-stock/{id}', 'App\Controllers\ProductoController', 'reducirStock');
        
        // Rutas de API para usuarios
        $this->add('POST', '/admin/usuarios/crear', 'App\Controllers\AdminController', 'crearUsuario');
        $this->add('POST', '/admin/usuarios/editar/{id}', 'App\Controllers\AdminController', 'editarUsuario');
        $this->add('POST', '/admin/usuarios/eliminar/{id}', 'App\Controllers\AdminController', 'eliminarUsuario');
        
        // Rutas de API para pedidos
        $this->add('POST', '/admin/pedidos/actualizar/{id}', 'App\Controllers\AdminController', 'actualizarPedido');
        $this->add('POST', '/admin/pedidos/eliminar/{id}', 'App\Controllers\AdminController', 'eliminarPedido');

        // Rutas de perfil de usuario
        $this->add('GET', '/usuario/perfil', 'App\\Controllers\\UsuarioController', 'perfil');
        $this->add('POST', '/usuario/actualizar', 'App\\Controllers\\UsuarioController', 'actualizarPerfil');
    }
} 