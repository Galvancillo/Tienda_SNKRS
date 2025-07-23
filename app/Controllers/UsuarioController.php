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
        // Obtener datos del usuario
        $usuario = $this->db->query("SELECT id, nombre, correo FROM usuario WHERE id = ?", [$id])->fetch();
        // Obtener direcciones de envío
        $direcciones = $this->db->query("SELECT * FROM direccionenvio WHERE id_usuario = ?", [$id])->fetchAll();
        // Obtener historial de pedidos
        $pedidos = $this->db->query("SELECT * FROM pedido WHERE id_usuario = ? ORDER BY fecha_pedido DESC", [$id])->fetchAll();
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