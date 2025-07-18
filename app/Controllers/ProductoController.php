<?php

namespace App\Controllers;

use App\Services\ProductoService;

class ProductoController {
    private $productoService;

    public function __construct() {
        $this->productoService = new ProductoService();
    }

    public function nuevo() {
        // Obtener productos nuevos
        $productos = $this->productoService->obtenerProductosNuevos();
        require_once __DIR__ . '/../Views/productos/lonuevo.php';
    }

    public function hombre() {
        // Obtener productos para hombre
        $productos = $this->productoService->obtenerProductosHombre();
        require_once __DIR__ . '/../Views/productos/hombre.php';
    }

    public function mujer() {
        // Obtener productos para mujer
        $productos = $this->productoService->obtenerProductosMujer();
        require_once __DIR__ . '/../Views/productos/mujer.php';
    }

    public function ninos() {
        // Obtener productos para niños
        $productos = $this->productoService->obtenerProductosNinos();
        require_once __DIR__ . '/../Views/productos/niños.php';
    }

    public function ofertas() {
        // Obtener productos en oferta
        $productos = $this->productoService->obtenerProductosOfertas();
        require_once __DIR__ . '/../Views/productos/ofertas.php';
    }

    public function snkrs() {
        // Obtener productos SNKRS especiales
        $productos = $this->productoService->obtenerProductosSnkrs();
        require_once __DIR__ . '/../Views/productos/snkrs.php';
    }
} 