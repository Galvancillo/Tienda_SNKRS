<?php
namespace App\Controllers;

use App\Core\Database;

class CarritoController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function agregar() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_SNKRS/public/login.php');
            exit();
        }

        if (!isset($_POST['producto_talla_id']) || !isset($_POST['cantidad'])) {
            http_response_code(400);
            echo 'Datos incompletos';
            exit();
        }

        $usuario_id = $_SESSION['usuario_id'];
        $producto_talla_id = intval($_POST['producto_talla_id']);
        $cantidad = intval($_POST['cantidad']);

        // Buscar carrito activo del usuario
        $stmt = $this->db->prepare('SELECT id FROM carrito WHERE id_usuario = ? AND activo = 1 LIMIT 1');
        $stmt->execute([$usuario_id]);
        $carrito = $stmt->fetch();

        if ($carrito) {
            $carrito_id = $carrito['id'];
        } else {
            // Crear carrito nuevo
            $stmt = $this->db->prepare('INSERT INTO carrito (id_usuario, activo) VALUES (?, 1)');
            $stmt->execute([$usuario_id]);
            $carrito_id = $this->db->lastInsertId();
        }

        // Verificar si ya existe ese producto+talla en el carrito
        $stmt = $this->db->prepare('SELECT id, cantidad FROM detallecarrito WHERE id_carrito = ? AND id_producto_talla = ?');
        $stmt->execute([$carrito_id, $producto_talla_id]);
        $detalle = $stmt->fetch();

        if ($detalle) {
            // Sumar cantidad
            $nueva_cantidad = $detalle['cantidad'] + $cantidad;
            $stmt = $this->db->prepare('UPDATE detallecarrito SET cantidad = ? WHERE id = ?');
            $stmt->execute([$nueva_cantidad, $detalle['id']]);
        } else {
            // Insertar nuevo detalle
            $stmt = $this->db->prepare('INSERT INTO detallecarrito (id_carrito, id_producto_talla, cantidad) VALUES (?, ?, ?)');
            $stmt->execute([$carrito_id, $producto_talla_id, $cantidad]);
        }

        // Redirigir al carrito o mostrar mensaje
        header('Location: /Tienda_SNKRS/app/Views/productos/carrito.php');
        exit();
    }

    public function ver() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_SNKRS/public/login.php');
            exit();
        }
        $usuario_id = $_SESSION['usuario_id'];

        // Procesar eliminación parcial de producto
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['eliminar'], $_POST['cantidad_eliminar'])) {
            $detalle_id = intval($_GET['eliminar']);
            $cantidad_eliminar = intval($_POST['cantidad_eliminar']);
            // Obtener cantidad actual
            $stmt = $this->db->prepare('SELECT cantidad FROM detallecarrito WHERE id = ?');
            $stmt->execute([$detalle_id]);
            $detalle = $stmt->fetch();
            if ($detalle) {
                $nueva_cantidad = $detalle['cantidad'] - $cantidad_eliminar;
                if ($nueva_cantidad > 0) {
                    $stmt = $this->db->prepare('UPDATE detallecarrito SET cantidad = ? WHERE id = ?');
                    $stmt->execute([$nueva_cantidad, $detalle_id]);
                } else {
                    $stmt = $this->db->prepare('DELETE FROM detallecarrito WHERE id = ?');
                    $stmt->execute([$detalle_id]);
                }
            }
            // Redirigir para evitar reenvío de formulario
            header('Location: /Tienda_SNKRS/public/productos/carrito');
            exit();
        }

        // Procesar sumar/restar cantidad
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidad_cambio'])) {
            if (isset($_GET['sumar'])) {
                $detalle_id = intval($_GET['sumar']);
                $cambio = intval($_POST['cantidad_cambio']);
                $stmt = $this->db->prepare('SELECT cantidad FROM detallecarrito WHERE id = ?');
                $stmt->execute([$detalle_id]);
                $detalle = $stmt->fetch();
                if ($detalle) {
                    $nueva_cantidad = $detalle['cantidad'] + $cambio;
                    $stmt = $this->db->prepare('UPDATE detallecarrito SET cantidad = ? WHERE id = ?');
                    $stmt->execute([$nueva_cantidad, $detalle_id]);
                }
                header('Location: /Tienda_SNKRS/public/productos/carrito');
                exit();
            } elseif (isset($_GET['restar'])) {
                $detalle_id = intval($_GET['restar']);
                $cambio = intval($_POST['cantidad_cambio']);
                $stmt = $this->db->prepare('SELECT cantidad FROM detallecarrito WHERE id = ?');
                $stmt->execute([$detalle_id]);
                $detalle = $stmt->fetch();
                if ($detalle) {
                    $nueva_cantidad = $detalle['cantidad'] + $cambio;
                    if ($nueva_cantidad > 0) {
                        $stmt = $this->db->prepare('UPDATE detallecarrito SET cantidad = ? WHERE id = ?');
                        $stmt->execute([$nueva_cantidad, $detalle_id]);
                    } else {
                        $stmt = $this->db->prepare('DELETE FROM detallecarrito WHERE id = ?');
                        $stmt->execute([$detalle_id]);
                    }
                }
                header('Location: /Tienda_SNKRS/public/productos/carrito');
                exit();
            }
        }

        // Buscar carrito activo
        $stmt = $this->db->prepare('SELECT id FROM carrito WHERE id_usuario = ? AND activo = 1 LIMIT 1');
        $stmt->execute([$usuario_id]);
        $carrito = $stmt->fetch();
        $productos = [];
        if ($carrito) {
            $carrito_id = $carrito['id'];
            // Obtener productos del carrito con detalles
            $sql = 'SELECT dc.id as detalle_id, p.nombre, p.descripcion, p.precio, p.imagen_url, t.talla, dc.cantidad
                    FROM detallecarrito dc
                    JOIN producto_talla pt ON dc.id_producto_talla = pt.id
                    JOIN producto p ON pt.id_producto = p.id
                    JOIN talla t ON pt.id_talla = t.id
                    WHERE dc.id_carrito = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$carrito_id]);
            $productos = $stmt->fetchAll();
        }
        $titulo = 'Carrito de compras';
        $css_adicional = 'hombre';
        $esCarrito = true;
        require __DIR__ . '/../Views/productos/carrito.php';
    }
} 