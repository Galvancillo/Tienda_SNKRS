<?php

namespace App\Controllers;

use App\Core\Database;

class UsuarioController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }
        $this->db = new Database();
    }

    public function perfil() {
        $id = $_SESSION['user_id'];
        $usuario = $this->db->query("SELECT id, nombre, correo FROM usuario WHERE id = ?", [$id])->fetch();
        require_once __DIR__ . '/../Views/usuario/perfil.php';
    }

    public function actualizarPerfil() {
        $id = $_SESSION['user_id'];
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contraseña = $_POST['contraseña'] ?? null;
        $params = [$nombre, $correo];
        $sql = "UPDATE usuario SET nombre = ?, correo = ?";
        if ($contraseña) {
            $sql .= ", contraseña = ?";
            $params[] = $contraseña;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $this->db->query($sql, $params);
        $_SESSION['nombre'] = $nombre;
        $_SESSION['email'] = $correo;
        header('Location: /Tienda_SNKRS/public/usuario/perfil?success=1');
        exit();
    }
} 