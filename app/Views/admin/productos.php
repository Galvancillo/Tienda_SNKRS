<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/admin.css">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="/Tienda_SNKRS/public/assets/img/logo.png" alt="SNKRS Logo">
            </div>
            <nav class="admin-nav">
                <ul>
                    <li>
                        <a href="/Tienda_SNKRS/public/admin"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="active">
                        <a href="/Tienda_SNKRS/public/admin/productos"><i class="fas fa-shoe-prints"></i> Productos</a>
                    </li>
                    <li>
                        <a href="/Tienda_SNKRS/public/admin/usuarios"><i class="fas fa-users"></i> Usuarios</a>
                    </li>
                    <li>
                        <a href="/Tienda_SNKRS/public/admin/pedidos"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                    </li>
                    <li>
                        <a href="/Tienda_SNKRS/public/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Contenido Principal -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Gestión de Productos</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></span>
                    <img src="https://via.placeholder.com/40" alt="Admin Avatar" class="avatar">
                </div>
            </header>

            <!-- Botón para agregar nuevo producto -->
            <div class="action-bar">
                <button class="action-button" onclick="mostrarFormularioProducto()">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </div>

            <!-- Tabla de productos -->
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td>
                                <?php if ($producto['imagen_url']): ?>
                                    <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="Producto" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50" alt="Sin imagen">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                            <td>
                                <button class="action-button" onclick="editarProducto(<?php echo $producto['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-button" onclick="eliminarProducto(<?php echo $producto['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para agregar/editar producto -->
    <div id="productoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Agregar Producto</h2>
            <form id="productoForm" class="admin-form">
                <input type="hidden" id="productoId" name="id">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                <button type="submit" class="action-button">Guardar</button>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .action-bar {
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }
    </style>

    <script>
        // Funciones para el manejo del modal
        function mostrarFormularioProducto() {
            document.getElementById('modalTitle').textContent = 'Agregar Producto';
            document.getElementById('productoForm').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('productoModal').style.display = 'block';
        }

        function editarProducto(id) {
            document.getElementById('modalTitle').textContent = 'Editar Producto';
            document.getElementById('productoId').value = id;
            // Aquí deberías cargar los datos del producto
            document.getElementById('productoModal').style.display = 'block';
        }

        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch(`/Tienda_SNKRS/public/admin/productos/eliminar/${id}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el producto');
                    }
                });
            }
        }

        // Cerrar modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('productoModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == document.getElementById('productoModal')) {
                document.getElementById('productoModal').style.display = 'none';
            }
        }

        // Manejar envío del formulario
        document.getElementById('productoForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('productoId').value;
            const url = id ? 
                `/Tienda_SNKRS/public/admin/productos/editar/${id}` : 
                '/Tienda_SNKRS/public/admin/productos/crear';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al guardar el producto');
                }
            });
        };
    </script>
</body>
</html> 