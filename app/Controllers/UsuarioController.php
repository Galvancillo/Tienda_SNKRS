<?php

namespace App\Controllers;

use App\Core\Database;

class UsuarioController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }
        $this->db = new Database();
    }

    public function perfil() {
        $id = $_SESSION['usuario_id'];
        // Obtener datos del usuario
        $usuario = $this->db->query("SELECT id, nombre, correo FROM usuario WHERE id = ?", [$id])->fetch();
        // Obtener direcciones de envío
        $direcciones = $this->db->query("SELECT * FROM direccionenvio WHERE id_usuario = ?", [$id])->fetchAll();
        // Obtener historial de pedidos
        $pedidos = $this->db->query("SELECT * FROM pedido WHERE id_usuario = ? ORDER BY fecha_pedido DESC", [$id])->fetchAll();
        require_once __DIR__ . '/../Views/usuario/perfil.php';
    }

    public function actualizarPerfil() {
        session_start();
        $id = $_SESSION['usuario_id'];
        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contraseña = $_POST['contraseña'] ?? null;

        $params = [$nombre, $correo];
        $sql = "UPDATE usuario SET nombre = ?, correo = ?";

        if ($contraseña) {
            // Hashea la contraseña antes de guardar
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql .= ", contraseña = ?";
            $params[] = $hash;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;

        try {
            $this->db->query($sql, $params);
            $_SESSION['nombre'] = $nombre;
            $_SESSION['email'] = $correo;
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar perfil.']);
        }
        exit;
    }

    public function guardarDireccion()
    {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $data = $_POST;

        // Validación básica
        $required = ['calle', 'numero', 'colonia', 'ciudad', 'estado', 'cp'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo $field"]);
                exit;
            }
        }

        try {
            $db = \App\Core\Database::getInstance();
            $sql = "INSERT INTO direccionenvio (id_usuario, calle, numero, colonia, ciudad, estado, cp, referencias)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $usuario_id,
                $data['calle'],
                $data['numero'],
                $data['colonia'],
                $data['ciudad'],
                $data['estado'],
                $data['cp'],
                $data['referencias'] ?? ''
            ];
            $db->query($sql, $params);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar dirección.']);
        }
        exit;
    }

    public function eliminarDireccion()
    {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $direccion_id = $_POST['id'] ?? null;
        if (!$direccion_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de dirección no proporcionado']);
            exit;
        }

        try {
            $db = \App\Core\Database::getInstance();
            // Solo elimina si la dirección pertenece al usuario
            $sql = "DELETE FROM direccionenvio WHERE id = ? AND id_usuario = ?";
            $params = [$direccion_id, $usuario_id];
            $db->query($sql, $params);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar dirección.']);
        }
        exit;
    }

    public function editarDireccion()
    {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $data = $_POST;
        $direccion_id = $data['id'] ?? null;

        if (!$direccion_id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de dirección no proporcionado']);
            exit;
        }

        $required = ['calle', 'numero', 'colonia', 'ciudad', 'estado', 'cp'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Falta el campo $field"]);
                exit;
            }
        }

        try {
            $db = \App\Core\Database::getInstance();
            $sql = "UPDATE direccionenvio SET calle=?, numero=?, colonia=?, ciudad=?, estado=?, cp=?, referencias=? WHERE id=? AND id_usuario=?";
            $params = [
                $data['calle'],
                $data['numero'],
                $data['colonia'],
                $data['ciudad'],
                $data['estado'],
                $data['cp'],
                $data['referencias'] ?? '',
                $direccion_id,
                $usuario_id
            ];
            $db->query($sql, $params);

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al editar dirección.']);
        }
        exit;
    }

    public function checkout() {
        session_start();
        try {
            $usuario_id = $_SESSION['usuario_id'];
            $db = \App\Core\Database::getInstance();
            $direcciones = $db->query("SELECT * FROM direccionenvio WHERE id_usuario = ?", [$usuario_id])->fetchAll();
            require __DIR__ . '/../Views/usuario/checkout.php';
        } catch (\Exception $e) {
            error_log('Error en checkout: ' . $e->getMessage());
            echo '<div style="color:#c00; text-align:center; margin:40px;">Error al cargar las direcciones. Intenta de nuevo.</div>';
        }
    }

    public function confirmarPedido() {
        session_start();
        try {
            if (!isset($_POST['direccion_id'])) {
                header('Location: /Tienda_SNKRS/public/usuario/checkout');
                exit;
            }
            $direccion_id = $_POST['direccion_id'];
            require __DIR__ . '/../Views/usuario/pago.php';
        } catch (\Exception $e) {
            error_log('Error en confirmarPedido: ' . $e->getMessage());
            echo '<div style="color:#c00; text-align:center; margin:40px;">Error al preparar el pago. Intenta de nuevo.</div>';
        }
    }

    public function finalizarPago() {
        session_start();
        try {
            $usuario_id = $_SESSION['usuario_id'];
            $direccion_id = $_POST['direccion_id'] ?? null;
            $nombre_tarjeta = $_POST['nombre_tarjeta'] ?? '';
            $numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
            $expiracion = $_POST['expiracion'] ?? '';
            $cvv = $_POST['cvv'] ?? '';
            $db = \App\Core\Database::getInstance();
            $db->beginTransaction();
            $carrito = $db->query("SELECT * FROM carrito WHERE id_usuario = ? AND activo = 1", [$usuario_id])->fetch();
            if (!$carrito) {
                $db->rollBack();
                header('Location: /Tienda_SNKRS/public/productos/carrito');
                exit;
            }
            $carrito_id = $carrito['id'];
            $productos = $db->query("SELECT * FROM detallecarrito WHERE id_carrito = ?", [$carrito_id])->fetchAll();
            if (empty($productos)) {
                $db->rollBack();
                header('Location: /Tienda_SNKRS/public/productos/carrito');
                exit;
            }
            $total = 0;
            foreach ($productos as $item) {
                $pt = $db->query("SELECT pt.*, p.precio FROM producto_talla pt JOIN producto p ON pt.id_producto = p.id WHERE pt.id = ?", [$item['id_producto_talla']])->fetch();
                $total += $pt['precio'] * $item['cantidad'];
            }
            $db->query("INSERT INTO pedido (id_usuario, estado, total) VALUES (?, 'pendiente', ?)", [$usuario_id, $total]);
            $pedido_id = $db->lastInsertId();
            foreach ($productos as $item) {
                $pt = $db->query("SELECT pt.*, p.precio FROM producto_talla pt JOIN producto p ON pt.id_producto = p.id WHERE pt.id = ?", [$item['id_producto_talla']])->fetch();
                $db->query("INSERT INTO detallepedido (id_pedido, id_producto_talla, cantidad, precio_unitario) VALUES (?, ?, ?, ?)", [
                    $pedido_id,
                    $item['id_producto_talla'],
                    $item['cantidad'],
                    $pt['precio']
                ]);
                // Descontar stock de la talla
                $db->query("UPDATE producto_talla SET stock = stock - ? WHERE id = ?", [
                    $item['cantidad'],
                    $item['id_producto_talla']
                ]);
            }
            // Actualizar el stock total del producto en la tabla producto
            $producto_ids = [];
            foreach ($productos as $item) {
                $pt = $db->query("SELECT id_producto FROM producto_talla WHERE id = ?", [$item['id_producto_talla']])->fetch();
                if ($pt) {
                    $producto_ids[] = $pt['id_producto'];
                }
            }
            $producto_ids = array_unique($producto_ids);
            foreach ($producto_ids as $producto_id) {
                $stock_total = $db->query("SELECT SUM(stock) as total FROM producto_talla WHERE id_producto = ?", [$producto_id])->fetch()['total'];
                $db->query("UPDATE producto SET stock = ? WHERE id = ?", [$stock_total, $producto_id]);
            }
            $db->query("INSERT INTO pago (id_pedido, metodo_pago, monto, estado) VALUES (?, 'Tarjeta', ?, 'procesando')", [$pedido_id, $total]);
            $db->query("DELETE FROM detallecarrito WHERE id_carrito = ?", [$carrito_id]);
            $db->query("UPDATE carrito SET activo = 0 WHERE id = ?", [$carrito_id]);
            $db->commit();
            header('Location: /Tienda_SNKRS/public/usuario/compra-exitosa?pedido_id=' . $pedido_id);
            exit;
        } catch (\Exception $e) {
            if (isset($db) && $db->isInTransaction()) {
                $db->rollBack();
            }
            error_log('Error en finalizarPago: ' . $e->getMessage());
            echo '<div style="color:#c00; text-align:center; margin:40px;">Error al procesar el pago. Intenta de nuevo.</div>';
        }
    }

    public function compraExitosa() {
        session_start();
        try {
            $usuario_id = $_SESSION['usuario_id'];
            $pedido_id = $_GET['pedido_id'] ?? null;
            $db = \App\Core\Database::getInstance();
            $pedido = $db->query("SELECT * FROM pedido WHERE id = ? AND id_usuario = ?", [$pedido_id, $usuario_id])->fetch();
            $detalles = $db->query("SELECT dp.*, pt.id_producto, p.nombre, p.imagen_url, pt.id_talla, t.talla FROM detallepedido dp JOIN producto_talla pt ON dp.id_producto_talla = pt.id JOIN producto p ON pt.id_producto = p.id JOIN talla t ON pt.id_talla = t.id WHERE dp.id_pedido = ?", [$pedido_id])->fetchAll();
            require __DIR__ . '/../Views/usuario/compra_exitosa.php';
        } catch (\Exception $e) {
            error_log('Error en compraExitosa: ' . $e->getMessage());
            echo '<div style="color:#c00; text-align:center; margin:40px;">Error al mostrar la confirmación. Intenta de nuevo.</div>';
        }
    }

    public function detallePedidoAjax($pedido_id) {
        session_start();
        try {
            $usuario_id = $_SESSION['usuario_id'];
            $db = \App\Core\Database::getInstance();
            $pedido = $db->query("SELECT * FROM pedido WHERE id = ? AND id_usuario = ?", [$pedido_id, $usuario_id])->fetch();
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
            error_log('Error en detallePedidoAjax: ' . $e->getMessage());
            echo "<div style='color:#c00;'>Error al cargar detalles del pedido.</div>";
            exit;
        }
    }
} 