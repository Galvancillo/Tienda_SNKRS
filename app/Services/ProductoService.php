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
                WHERE c.nombre = 'Hombre'";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosMujer() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'Mujer'";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosNinos() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'Niños'";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosOfertas() {
        // Aquí podrías agregar lógica para productos en oferta
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE p.precio < (SELECT AVG(precio) FROM producto)";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerProductosSnkrs() {
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p 
                LEFT JOIN categoria c ON p.id_categoria = c.id 
                WHERE c.nombre = 'SNKRS'";
        return $this->db->query($sql)->fetchAll();
    }
} 