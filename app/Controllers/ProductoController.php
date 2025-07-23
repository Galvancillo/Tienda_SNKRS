<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\ProductoService;

class ProductoController {
    private $db;
    private $uploadDir;
    private $productoService;

    public function __construct() {
        $this->db = new Database();
        $this->uploadDir = __DIR__ . '/../../public/assets/img/productos/';
        
        // Crear el directorio si no existe
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }

        $this->productoService = new ProductoService();
    }

    private function handleImageUpload($file) {
        try {
            if (!isset($file['error']) || is_array($file['error'])) {
                throw new \RuntimeException('Invalid parameters.');
            }

            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    return null;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            if ($file['size'] > 5242880) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif'
            ];

            if (!array_key_exists($mimeType, $allowedTypes)) {
                throw new \RuntimeException('Invalid file format.');
            }

            // Generar nombre único para la imagen
            $extension = $allowedTypes[$mimeType];
            $imageName = sprintf('%s.%s', sha1_file($file['tmp_name']), $extension);
            $uploadPath = $this->uploadDir . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }

            return [
                'nombre' => $imageName,
                'tipo' => $mimeType,
                'url' => '/Tienda_SNKRS/public/assets/img/productos/' . $imageName
            ];

        } catch (\RuntimeException $e) {
            error_log("Error al subir imagen: " . $e->getMessage());
            return null;
        }
    }

    public function crearProducto() {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            $id_categoria = $_POST['categoria'] ?? null;

            // Calcular stock total sumando los valores de stock_talla
            $stock = 0;
            if (isset($_POST['stock_talla']) && is_array($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $valor) {
                    if (is_numeric($valor)) {
                        $stock += (int)$valor;
                    }
                }
            }

            // Manejar la subida de imagen
            $imagenInfo = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
                $imagenInfo = $this->handleImageUpload($_FILES['imagen']);
            }

            $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, id_categoria, imagen_url, imagen_nombre, imagen_tipo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $nombre,
                $descripcion,
                $precio,
                $stock, 
                $id_categoria,
                $imagenInfo ? $imagenInfo['url'] : null,
                $imagenInfo ? $imagenInfo['nombre'] : null,
                $imagenInfo ? $imagenInfo['tipo'] : null
            ];
            $this->db->query($sql, $params);
            $id_producto = $this->db->lastInsertId();

            // Guardar stock por talla
            if (isset($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $id_talla => $stock) {
                    $this->db->query("INSERT INTO producto_talla (id_producto, id_talla, stock) VALUES (?, ?, ?)", [$id_producto, $id_talla, $stock]);
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            error_log("Error al crear producto: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function editarProducto($id) {
        try {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? 0;
            $id_categoria = $_POST['categoria'] ?? null;

            // Calcular stock total sumando los valores de stock_talla
            $stock = 0;
            if (isset($_POST['stock_talla']) && is_array($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $valor) {
                    if (is_numeric($valor)) {
                        $stock += (int)$valor;
                    }
                }
            }

            // Obtener información actual del producto
            $producto = $this->db->query("SELECT imagen_nombre FROM producto WHERE id = ?", [$id])->fetch();

            // Manejar la subida de imagen
            $imagenInfo = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
                $imagenInfo = $this->handleImageUpload($_FILES['imagen']);
                // Eliminar imagen anterior si existe
                if ($imagenInfo && $producto && $producto['imagen_nombre']) {
                    $imagenAnterior = $this->uploadDir . $producto['imagen_nombre'];
                    if (file_exists($imagenAnterior)) {
                        unlink($imagenAnterior);
                    }
                }
            }

            if ($imagenInfo) {
                $sql = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, id_categoria = ?, imagen_url = ?, imagen_nombre = ?, imagen_tipo = ? WHERE id = ?";
                $params = [
                    $nombre, $descripcion, $precio, $id_categoria,
                    $imagenInfo['url'], $imagenInfo['nombre'], $imagenInfo['tipo'],
                    $id
                ];
            } else {
                $sql = "UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, id_categoria = ? WHERE id = ?";
                $params = [$nombre, $descripcion, $precio, $id_categoria, $id];
            }
            $this->db->query($sql, $params);

            // Actualizar stock por talla: eliminar y volver a insertar
            $this->db->query("DELETE FROM producto_talla WHERE id_producto = ?", [$id]);
            if (isset($_POST['stock_talla'])) {
                foreach ($_POST['stock_talla'] as $id_talla => $stock) {
                    $this->db->query("INSERT INTO producto_talla (id_producto, id_talla, stock) VALUES (?, ?, ?)", [$id, $id_talla, $stock]);
                }
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            error_log("Error al editar producto: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function eliminarProducto($id) {
        // Asegurarse de que la respuesta sea JSON desde el principio
        header('Content-Type: application/json');
        
        try {
            // Validar que el ID sea un número
            if (!is_numeric($id)) {
                throw new \Exception('ID de producto inválido');
            }

            // Iniciar transacción
            $this->db->beginTransaction();

            // Verificar si el producto existe y obtener su información
            $producto = $this->db->query(
                "SELECT id, imagen_nombre FROM producto WHERE id = ?", 
                [$id]
            )->fetch();

            if (!$producto) {
                throw new \Exception('Producto no encontrado');
            }

            // Eliminar la imagen si existe
            if ($producto['imagen_nombre']) {
                $imagenPath = $this->uploadDir . $producto['imagen_nombre'];
                if (file_exists($imagenPath)) {
                    if (!unlink($imagenPath)) {
                        error_log("No se pudo eliminar la imagen: " . $imagenPath);
                    }
                }
            }

            // Eliminar el producto
            $result = $this->db->query("DELETE FROM producto WHERE id = ?", [$id]);
            
            if ($result === false) {
                throw new \Exception('Error al eliminar el producto de la base de datos');
            }

            // Confirmar transacción
            $this->db->commit();
            
            echo json_encode(['success' => true]);
            
        } catch (\Exception $e) {
            // Revertir cambios si algo salió mal
            if ($this->db->isInTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("Error al eliminar producto: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'error' => 'Error al eliminar el producto: ' . $e->getMessage()
            ]);
        } catch (\Error $e) {
            // Capturar errores fatales de PHP
            if ($this->db->isInTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("Error fatal al eliminar producto: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'error' => 'Error interno del servidor'
            ]);
        }
    }

    public function obtenerProducto($id) {
        try {
            // Asegurarse de que la respuesta sea JSON
            header('Content-Type: application/json');

            $sql = "SELECT id, nombre, descripcion, precio, stock, imagen_url FROM producto WHERE id = ?";
            $producto = $this->db->query($sql, [$id])->fetch();
            
            if (!$producto) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Producto no encontrado'
                ]);
                return;
            }

            echo json_encode($producto);
        } catch (\Exception $e) {
            error_log("Error al obtener producto: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener el producto: ' . $e->getMessage()
            ]);
        }
    }

    public function reducirStock($id) {
        // Asegurarse de que la respuesta sea JSON desde el principio
        header('Content-Type: application/json');
        
        try {
            // Validar que el ID sea un número
            if (!is_numeric($id)) {
                throw new \Exception('ID de producto inválido');
            }

            // Obtener el JSON del body de la petición
            $input = json_decode(file_get_contents('php://input'), true);
            $cantidad = $input['cantidad'] ?? 0;

            if (!is_numeric($cantidad) || $cantidad <= 0) {
                throw new \Exception('Cantidad inválida');
            }

            // Obtener el stock actual del producto
            $producto = $this->db->query(
                "SELECT id, stock FROM producto WHERE id = ?", 
                [$id]
            )->fetch();

            if (!$producto) {
                throw new \Exception('Producto no encontrado');
            }

            $stockActual = (int)$producto['stock'];
            
            if ($cantidad > $stockActual) {
                throw new \Exception('La cantidad a reducir no puede ser mayor al stock actual');
            }

            // Calcular nuevo stock
            $nuevoStock = $stockActual - $cantidad;

            // Actualizar el stock
            $result = $this->db->query(
                "UPDATE producto SET stock = ? WHERE id = ?", 
                [$nuevoStock, $id]
            );
            
            if ($result === false) {
                throw new \Exception('Error al actualizar el stock');
            }

            echo json_encode([
                'success' => true,
                'stock_anterior' => $stockActual,
                'stock_nuevo' => $nuevoStock,
                'cantidad_reducida' => $cantidad
            ]);
            
        } catch (\Exception $e) {
            error_log("Error al reducir stock: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => 'Error al reducir el stock: ' . $e->getMessage()
            ]);
        } catch (\Error $e) {
            error_log("Error fatal al reducir stock: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'error' => 'Error interno del servidor'
            ]);
        }
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

    public function carrito() {
        require_once __DIR__ . '/../Views/productos/carrito.php';
    }

    public function obtenerTallas() {
        header('Content-Type: application/json');
        try {
            $tallas = $this->productoService->obtenerTodasLasTallas();
            echo json_encode(['success' => true, 'tallas' => $tallas]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function obtenerCategorias() {
        header('Content-Type: application/json');
        try {
            $categorias = $this->productoService->obtenerTodasLasCategorias();
            echo json_encode(['success' => true, 'categorias' => $categorias]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function detalleProducto($id) {
        header('Content-Type: application/json');
        try {
            $producto = $this->db->query("SELECT * FROM producto WHERE id = ?", [$id])->fetch();
            if (!$producto) {
                echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
                return;
            }
            // Obtener tallas y stock
            $tallas = $this->db->query(
                "SELECT t.id, t.talla, pt.stock FROM producto_talla pt JOIN talla t ON pt.id_talla = t.id WHERE pt.id_producto = ? ORDER BY t.talla ASC",
                [$id]
            )->fetchAll();
            $producto['tallas'] = $tallas;
            echo json_encode(['success' => true, 'producto' => $producto]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function agregarAlCarrito() {
        header('Content-Type: application/json');
        session_start();
        try {
            $id_producto = $_POST['id_producto'] ?? null;
            $id_talla = $_POST['id_talla'] ?? null;
            $cantidad = $_POST['cantidad'] ?? 1;
            if (!$id_producto || !$id_talla) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            }
            // Validar stock
            $row = $this->db->query("SELECT stock FROM producto_talla WHERE id_producto = ? AND id_talla = ?", [$id_producto, $id_talla])->fetch();
            if (!$row || $row['stock'] < $cantidad) {
                echo json_encode(['success' => false, 'error' => 'Stock insuficiente']);
                return;
            }
            // Guardar en sesión
            if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
            $key = $id_producto . '-' . $id_talla;
            if (isset($_SESSION['carrito'][$key])) {
                $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
            } else {
                $_SESSION['carrito'][$key] = [
                    'id_producto' => $id_producto,
                    'id_talla' => $id_talla,
                    'cantidad' => $cantidad
                ];
            }
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
} 