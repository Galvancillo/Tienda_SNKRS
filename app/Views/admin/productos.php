<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="/Tienda_SNKRS/public/assets/img/logo.png">
    <title>Gestión de Productos - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/admin.css">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos para el layout principal */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--light-gray);
        }

        .admin-container {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 50px); /* Altura total menos el footer */
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 250px; /* Mismo ancho que el sidebar */
            padding: 20px;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding-bottom: 70px; /* espacio para el footer */
        }

        /* Contenedor de la tabla */
        .table-container {
            flex: 1;
            margin-bottom: 20px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            z-index: 100;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .admin-container {
                flex-direction: column;
            }
        }

        /* Resto de tus estilos existentes */
        #imagenPreview {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        #previewImg {
            display: block;
            margin: 0 auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-group small {
            display: block;
            color: #666;
            margin-top: 5px;
            font-size: 0.8em;
        }

        .imagen-producto {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .action-bar {
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .admin-table th {
            background-color: var(--primary-color);
            color: white;
        }

        .admin-table tr:hover {
            background-color: #f5f5f5;
        }
    </style>
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
                    <img src="/Tienda_SNKRS/public/assets/img/logo.png" alt="Admin Avatar" class="avatar">
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
                                    <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="Producto" class="imagen-producto">
                                <?php else: ?>
                                    <img src="/Tienda_SNKRS/public/assets/img/logo.png" alt="Sin imagen" class="imagen-producto">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                            <td>
                                <button class="action-button" onclick="editarProducto(<?php echo $producto['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-button" onclick="reducirStock(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>', <?php echo $producto['stock']; ?>)">
                                    <i class="fas fa-minus"></i>
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

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
    </footer>

    <!-- Modal para agregar/editar producto -->
    <div id="productoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Agregar Producto</h2>
            <form id="productoForm" class="admin-form" enctype="multipart/form-data">
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
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria" required></select>
                </div>
                <div class="form-group">
                    <label>Stock por Talla</label>
                    <div id="tallasContainer" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                    <small>Ingresa el stock para cada talla disponible</small>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen</label>
                    <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/gif">
                    <div id="imagenPreview" style="margin-top: 10px; display: none;">
                        <img id="previewImg" src="" alt="Vista previa" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <small>Formatos permitidos: PNG, JPG, GIF. Tamaño máximo: 5MB</small>
                </div>
                <button type="submit" class="action-button">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Modal para reducir stock -->
    <div id="stockModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalStock()">&times;</span>
            <h2>Reducir Stock</h2>
            <form id="stockForm" class="admin-form">
                <input type="hidden" id="stockProductoId" name="id">
                <div class="form-group">
                    <label for="productoNombre">Producto:</label>
                    <input type="text" id="productoNombre" readonly>
                </div>
                <div class="form-group">
                    <label for="stockActual">Stock Actual:</label>
                    <input type="number" id="stockActual" readonly>
                </div>
                <div class="form-group">
                    <label for="cantidadReducir">Cantidad a Reducir:</label>
                    <input type="number" id="cantidadReducir" name="cantidad" min="1" required>
                    <small>Ingresa la cantidad que deseas reducir del stock</small>
                </div>
                <button type="submit" class="action-button">Reducir Stock</button>
            </form>
        </div>
    </div>

    <script>
        // Función para mostrar vista previa de la imagen
        document.getElementById('imagen').onchange = function(e) {
            const preview = document.getElementById('imagenPreview');
            const previewImg = document.getElementById('previewImg');
            const file = e.target.files[0];

            if (file) {
                // Verificar el tamaño del archivo (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. El tamaño máximo es 5MB.');
                    e.target.value = '';
                    preview.style.display = 'none';
                    return;
                }

                // Verificar el tipo de archivo
                if (!file.type.match('image/png|image/jpeg|image/gif')) {
                    alert('Formato de archivo no permitido. Use PNG, JPG o GIF.');
                    e.target.value = '';
                    preview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        };

        // Cargar categorías y tallas al abrir el modal
        function cargarCategoriasYTallas() {
            // Cargar categorías
            fetch('/Tienda_SNKRS/public/admin/productos/categorias')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('categoria');
                    select.innerHTML = '';
                    if (data.success) {
                        data.categorias.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.id;
                            option.textContent = cat.nombre;
                            select.appendChild(option);
                        });
                    }
                });
            // Cargar tallas
            fetch('/Tienda_SNKRS/public/admin/productos/tallas')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('tallasContainer');
                    container.innerHTML = '';
                    if (data.success) {
                        data.tallas.forEach(talla => {
                            const div = document.createElement('div');
                            div.style.display = 'flex';
                            div.style.alignItems = 'center';
                            div.innerHTML = `<label style='margin-right:4px;'>${talla.talla}</label><input type='number' min='0' name='stock_talla[${talla.id}]' style='width:60px;' value='0'>`;
                            container.appendChild(div);
                        });
                    }
                });
        }
        // Mostrar modal y cargar datos
        function mostrarFormularioProducto() {
            document.getElementById('modalTitle').textContent = 'Agregar Producto';
            document.getElementById('productoForm').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('imagenPreview').style.display = 'none';
            cargarCategoriasYTallas();
            document.getElementById('productoModal').style.display = 'block';
        }

        // Función para editar producto
        function editarProducto(id) {
            document.getElementById('modalTitle').textContent = 'Editar Producto';
            document.getElementById('productoId').value = id;
            
            // Obtener datos del producto
            fetch(`/Tienda_SNKRS/public/admin/productos/obtener/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(producto => {
                    document.getElementById('nombre').value = producto.nombre;
                    document.getElementById('descripcion').value = producto.descripcion;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('categoria').value = producto.categoria_id; // Asignar categoría
                    
                    // Asignar stocks por talla
                    const tallasContainer = document.getElementById('tallasContainer');
                    tallasContainer.innerHTML = ''; // Limpiar antes de cargar
                    if (producto.stocks_por_talla) {
                        producto.stocks_por_talla.forEach(stock => {
                            const div = document.createElement('div');
                            div.style.display = 'flex';
                            div.style.alignItems = 'center';
                            div.innerHTML = `<label style='margin-right:4px;'>${stock.talla.talla}</label><input type='number' min='0' name='stock_talla[${stock.talla.id}]' style='width:60px;' value='${stock.stock}'>`;
                            tallasContainer.appendChild(div);
                        });
                    }

                    // Mostrar imagen actual si existe
                    if (producto.imagen_url) {
                        document.getElementById('previewImg').src = producto.imagen_url;
                        document.getElementById('imagenPreview').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del producto');
                });

            document.getElementById('productoModal').style.display = 'block';
        }

        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch(`/Tienda_SNKRS/public/admin/productos/eliminar/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    // Verificar si la respuesta es JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Respuesta del servidor no es JSON válido');
                    }
                    
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        throw new Error(data.error || 'Error al eliminar el producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el producto: ' + error.message);
                });
            }
        }

        function reducirStock(id, nombre, stockActual) {
            document.getElementById('stockProductoId').value = id;
            document.getElementById('productoNombre').value = nombre;
            document.getElementById('stockActual').value = stockActual;
            document.getElementById('cantidadReducir').max = stockActual;
            document.getElementById('cantidadReducir').value = '';
            document.getElementById('stockModal').style.display = 'block';
        }

        function cerrarModalStock() {
            document.getElementById('stockModal').style.display = 'none';
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
            if (event.target == document.getElementById('stockModal')) {
                document.getElementById('stockModal').style.display = 'none';
            }
        }

        // Manejar envío del formulario
        document.getElementById('productoForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            // Agregar manualmente los stocks por talla
            document.querySelectorAll('#tallasContainer input').forEach(input => {
                formData.append(input.name, input.value);
            });
            const id = document.getElementById('productoId').value;
            const url = id ? 
                `/Tienda_SNKRS/public/admin/productos/editar/${id}` : 
                '/Tienda_SNKRS/public/admin/productos/crear';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    throw new Error(data.error || 'Error al guardar el producto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar el producto: ' + error.message);
            });
        };

        // Manejar envío del formulario de reducción de stock
        document.getElementById('stockForm').onsubmit = function(e) {
            e.preventDefault();
            const id = document.getElementById('stockProductoId').value;
            const cantidad = document.getElementById('cantidadReducir').value;
            const stockActual = parseInt(document.getElementById('stockActual').value);

            if (parseInt(cantidad) > stockActual) {
                alert('La cantidad a reducir no puede ser mayor al stock actual');
                return;
            }

            fetch(`/Tienda_SNKRS/public/admin/productos/reducir-stock/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    cantidad: parseInt(cantidad)
                })
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Respuesta del servidor no es JSON válido');
                }
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Stock reducido exitosamente');
                    location.reload();
                } else {
                    throw new Error(data.error || 'Error al reducir el stock');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al reducir el stock: ' + error.message);
            });
        };
    </script>
</body>
</html> 