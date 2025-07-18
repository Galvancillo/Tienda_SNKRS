<?php

namespace App\Services;

use App\Core\Database;

class ProductoService {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function obtenerProductosNuevos() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                ORDER BY p.id DESC LIMIT 12";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosHombre() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'Hombre' OR FIND_IN_SET(c.id, p.categorias_referencia) > 0";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosMujer() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'Mujer' OR FIND_IN_SET(c.id, p.categorias_referencia) > 0";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosNinos() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'Niños' OR FIND_IN_SET(c.id, p.categorias_referencia) > 0";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosOfertas() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE (p.precio < (SELECT AVG(precio) FROM producto)) OR FIND_IN_SET(c.id, p.categorias_referencia) > 0";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosSnkrs() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'SNKRS' OR FIND_IN_SET(c.id, p.categorias_referencia) > 0";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerTodasLasTallas() {
        $sql = "SELECT * FROM talla ORDER BY talla ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerTodasLasCategorias() {
        $sql = "SELECT * FROM categoria ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }
} 