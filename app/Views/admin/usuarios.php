<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="/Tienda_SNKRS/public/assets/img/logo.png">
    <title>Gestión de Usuarios - SNKRS</title>
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
                    <li>
                        <a href="/Tienda_SNKRS/public/admin/productos"><i class="fas fa-shoe-prints"></i> Productos</a>
                    </li>
                    <li class="active">
                        <a href="/Tienda_SNKRS/public/admin/usuarios"><i class="fas fa-users"></i> Usuarios</a>
                    </li>
                    <li>
                        <a href="/Tienda_SNKRS/public/admin/pedidos"><i class="fas fa-shopping-cart"></i> Pedidos</a>
                    </li>
                    <li>
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <a href="/Tienda_SNKRS/public/usuario/perfil" class="icon" title="Editar Perfil">👤</a>
                        <?php else: ?>
                            <a href="/Tienda_SNKRS/public/login" class="icon" title="Iniciar Sesión">👤</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Contenido Principal -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Gestión de Usuarios</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></span>
                    <img src="https://via.placeholder.com/40" alt="Admin Avatar" class="avatar">
                </div>
            </header>

            <!-- Botón para agregar nuevo usuario -->
            <div class="action-bar">
                <button class="action-button" onclick="mostrarFormularioUsuario()">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </button>
            </div>

            <!-- Tabla de usuarios -->
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                            <td>
                                <button class="action-button" onclick="editarUsuario(<?php echo $usuario['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-button" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">
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

    <!-- Modal para agregar/editar usuario -->
    <div id="usuarioModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Agregar Usuario</h2>
            <form id="usuarioForm" class="admin-form">
                <input type="hidden" id="usuarioId" name="id">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" id="contraseña" name="contraseña">
                    <small>Dejar en blanco para mantener la contraseña actual al editar</small>
                </div>
                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol" required>
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
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

        small {
            color: #666;
            font-size: 0.8em;
            display: block;
            margin-top: 5px;
        }
    </style>

    <script>
        // Funciones para el manejo del modal
        function mostrarFormularioUsuario() {
            document.getElementById('modalTitle').textContent = 'Agregar Usuario';
            document.getElementById('usuarioForm').reset();
            document.getElementById('usuarioId').value = '';
            document.getElementById('contraseña').required = true;
            document.getElementById('usuarioModal').style.display = 'block';
        }

        function editarUsuario(id) {
            document.getElementById('modalTitle').textContent = 'Editar Usuario';
            document.getElementById('usuarioId').value = id;
            document.getElementById('contraseña').required = false;
            // Aquí deberías cargar los datos del usuario
            document.getElementById('usuarioModal').style.display = 'block';
        }

        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                fetch(`/Tienda_SNKRS/public/admin/usuarios/eliminar/${id}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el usuario');
                    }
                });
            }
        }

        // Cerrar modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('usuarioModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == document.getElementById('usuarioModal')) {
                document.getElementById('usuarioModal').style.display = 'none';
            }
        }

        // Manejar envío del formulario
        document.getElementById('usuarioForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('usuarioId').value;
            const url = id ? 
                `/Tienda_SNKRS/public/admin/usuarios/editar/${id}` : 
                '/Tienda_SNKRS/public/admin/usuarios/crear';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al guardar el usuario');
                }
            });
        };
    </script>
</body>
</html> 