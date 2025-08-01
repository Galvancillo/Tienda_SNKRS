<?php

namespace App\Controllers;

use App\Core\Database;

class AdminController {
    private $db;

    public function __construct() {
        // Verificar si el usuario está autenticado y es administrador
        if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }
        $this->db = new Database();
    }

    public function index() {
        $data = [
            'totalProductos' => $this->obtenerTotalProductos(),
            'totalUsuarios' => $this->obtenerTotalUsuarios(),
            'totalPedidos' => $this->obtenerTotalPedidos(),
            'totalVentas' => $this->obtenerTotalVentas(),
            'actividades' => $this->obtenerActividadReciente()
        ];
        
        // Cargar la vista del dashboard con los datos
        extract($data);
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function productos() {
        $productos = $this->db->query("SELECT * FROM producto ORDER BY fecha_registro DESC")->fetchAll();
        require_once __DIR__ . '/../Views/admin/productos.php';
    }

    public function usuarios() {
        $usuarios = $this->db->query("SELECT * FROM usuario ORDER BY fecha_registro DESC")->fetchAll();
        require_once __DIR__ . '/../Views/admin/usuarios.php';
    }

    public function pedidos() {
        $pedidos = $this->db->query("
            SELECT p.*, u.nombre as nombre_usuario 
            FROM pedido p 
            JOIN usuario u ON p.id_usuario = u.id 
            ORDER BY fecha_pedido DESC
        ")->fetchAll();
        require_once __DIR__ . '/../Views/admin/pedidos.php';
    }

    public function detallePedidoAjax($pedido_id) {
        try {
            $db = $this->db;
            $pedido = $db->query("SELECT * FROM pedido WHERE id = ?", [$pedido_id])->fetch();
            if (!$pedido) {
                http_response_code(404);
                echo "<div style='color:#c00;'>Pedido no encontrado.</div>";
                exit;
            }
            $detalles = $db->query("SELECT dp.*, pt.id_producto, p.nombre, p.imagen_url, pt.id_talla, t.talla FROM detallepedido dp JOIN producto_talla pt ON dp.id_producto_talla = pt.id JOIN producto p ON pt.id_producto = p.id JOIN talla t ON pt.id_talla = t.id WHERE dp.id_pedido = ?", [$pedido_id])->fetchAll();
            ob_start();
            ?>
            <div>
                <div style="font-weight:bold; margin-bottom:8px;">Estado: <?= htmlspecialchars(ucfirst($pedido['estado'])) ?></div>
                <?php foreach ($detalles as $item): ?>
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                        <div>
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong><br>
                            Talla: <?= htmlspecialchars($item['talla']) ?> | Cantidad: <?= htmlspecialchars($item['cantidad']) ?><br>
                            $<?= number_format($item['precio_unitario'], 2) ?> c/u
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="font-weight:bold; margin-top:10px;">Total: $<?= number_format($pedido['total'], 2) ?></div>
            </div>
            <?php
            echo ob_get_clean();
            exit;
        } catch (\Exception $e) {
            error_log('Error en detallePedidoAjax (admin): ' . $e->getMessage());
            echo "<div style='color:#c00;'>Error al cargar detalles del pedido.</div>";
            exit;
        }
    }

    // Métodos CRUD para productos
    public function crearProducto() {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;

            // Calcular stock total sumando los valores de stock_talla
            $stock = 0;
            if (isset($_POST['stock_talla']) && is_array($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $valor) {
                    if (is_numeric($valor)) {
                        $stock += (int)$valor;
                    }
                }
            }

            $sql = "INSERT INTO producto (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
            $this->db->query($sql, [$nombre, $descripcion, $precio, $stock]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function editarProducto($id) {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;

            // Calcular stock total sumando los valores de stock_talla
            $stock = 0;
            if (isset($_POST['stock_talla']) && is_array($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $valor) {
                    if (is_numeric($valor)) {
                        $stock += (int)$valor;
                    }
                }
            }

            $sql = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?";
            $this->db->query($sql, [$nombre, $descripcion, $precio, $stock, $id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Métodos CRUD para usuarios
    public function crearUsuario() {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $contraseña = $_POST['contraseña'] ?? '';
            $rol = $_POST['rol'] ?? 'usuario';

            $sql = "INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
            $this->db->query($sql, [$nombre, $correo, $contraseña, $rol]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function editarUsuario($id) {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $rol = $_POST['rol'] ?? 'usuario';

            $sql = "UPDATE usuario SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
            $this->db->query($sql, [$nombre, $correo, $rol, $id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function eliminarUsuario($id) {
        try {
            $sql = "DELETE FROM usuario WHERE id = ?";
            $this->db->query($sql, [$id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Métodos para pedidos
    public function actualizarPedido($id) {
        try {
            $estado = $_POST['estado'] ?? 'pendiente';

            $sql = "UPDATE pedido SET estado = ? WHERE id = ?";
            $this->db->query($sql, [$estado, $id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function eliminarPedido($id) {
        try {
            $sql = "DELETE FROM pedido WHERE id = ?";
            $this->db->query($sql, [$id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Métodos para obtener estadísticas
    private function obtenerTotalProductos() {
        try {
            $result = $this->db->query("SELECT COUNT(*) as total FROM producto")->fetch();
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            error_log("Error al obtener total de productos: " . $e->getMessage());
            return 0;
        }
    }

    private function obtenerTotalUsuarios() {
        try {
            $result = $this->db->query("SELECT COUNT(*) as total FROM usuario")->fetch();
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            error_log("Error al obtener total de usuarios: " . $e->getMessage());
            return 0;
        }
    }

    private function obtenerTotalPedidos() {
        try {
            $result = $this->db->query("SELECT COUNT(*) as total FROM pedido")->fetch();
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            error_log("Error al obtener total de pedidos: " . $e->getMessage());
            return 0;
        }
    }

    private function obtenerTotalVentas() {
        try {
            $result = $this->db->query("SELECT COALESCE(SUM(total), 0) as total FROM pedido")->fetch();
            return $result['total'] ?? 0;
        } catch (\Exception $e) {
            error_log("Error al obtener total de ventas: " . $e->getMessage());
            return 0;
        }
    }

    private function obtenerActividadReciente() {
        try {
            $actividades = [];

            // Obtener últimos productos agregados
            $sql = "SELECT nombre, fecha_registro FROM producto ORDER BY fecha_registro DESC LIMIT 3";
            $productos = $this->db->query($sql)->fetchAll();

            foreach ($productos as $producto) {
                $actividades[] = [
                    'tipo' => 'producto',
                    'mensaje' => "Nuevo producto agregado: " . htmlspecialchars($producto['nombre']),
                    'fecha' => $producto['fecha_registro']
                ];
            }

            // Obtener últimos pedidos
            $sql = "SELECT id, fecha_pedido FROM pedido ORDER BY fecha_pedido DESC LIMIT 3";
            $pedidos = $this->db->query($sql)->fetchAll();

            foreach ($pedidos as $pedido) {
                $actividades[] = [
                    'tipo' => 'pedido',
                    'mensaje' => "Nuevo pedido #" . htmlspecialchars($pedido['id']),
                    'fecha' => $pedido['fecha_pedido']
                ];
            }

            // Obtener últimos usuarios registrados
            $sql = "SELECT nombre, fecha_registro FROM usuario ORDER BY fecha_registro DESC LIMIT 3";
            $usuarios = $this->db->query($sql)->fetchAll();

            foreach ($usuarios as $usuario) {
                $actividades[] = [
                    'tipo' => 'usuario',
                    'mensaje' => "Nuevo usuario registrado: " . htmlspecialchars($usuario['nombre']),
                    'fecha' => $usuario['fecha_registro']
                ];
            }

            // Ordenar actividades por fecha
            usort($actividades, function($a, $b) {
                return strtotime($b['fecha']) - strtotime($a['fecha']);
            });

            return array_slice($actividades, 0, 5);
        } catch (\Exception $e) {
            error_log("Error al obtener actividad reciente: " . $e->getMessage());
            return [];
        }
    }
} 