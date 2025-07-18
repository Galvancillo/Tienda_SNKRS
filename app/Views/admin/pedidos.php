<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - SNKRS</title>
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
                    <li>
                        <a href="/Tienda_SNKRS/public/admin/usuarios"><i class="fas fa-users"></i> Usuarios</a>
                    </li>
                    <li class="active">
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
                <h1>Gestión de Pedidos</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></span>
                    <img src="https://via.placeholder.com/40" alt="Admin Avatar" class="avatar">
                </div>
            </header>

            <!-- Tabla de pedidos -->
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                            <td>
                                <select 
                                    class="estado-pedido" 
                                    onchange="actualizarEstadoPedido(<?php echo $pedido['id']; ?>, this.value)"
                                    style="background-color: <?php echo getEstadoColor($pedido['estado']); ?>"
                                >
                                    <option value="pendiente" <?php echo $pedido['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="procesando" <?php echo $pedido['estado'] === 'procesando' ? 'selected' : ''; ?>>Procesando</option>
                                    <option value="enviado" <?php echo $pedido['estado'] === 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                    <option value="entregado" <?php echo $pedido['estado'] === 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                    <option value="cancelado" <?php echo $pedido['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                            </td>
                            <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                            <td>
                                <button class="action-button" onclick="verDetallesPedido(<?php echo $pedido['id']; ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-button" onclick="eliminarPedido(<?php echo $pedido['id']; ?>)">
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

    <!-- Modal para detalles del pedido -->
    <div id="detallesPedidoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Detalles del Pedido</h2>
            <div id="detallesPedidoContenido"></div>
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

        .estado-pedido {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            color: white;
        }

        .table-container {
            overflow-x: auto;
        }
    </style>

    <script>
        function getEstadoColor(estado) {
            const colores = {
                'pendiente': '#f1c40f',
                'procesando': '#3498db',
                'enviado': '#2ecc71',
                'entregado': '#27ae60',
                'cancelado': '#e74c3c'
            };
            return colores[estado] || '#95a5a6';
        }

        function actualizarEstadoPedido(id, estado) {
            fetch(`/Tienda_SNKRS/public/admin/pedidos/actualizar/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `estado=${estado}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = event.target;
                    select.style.backgroundColor = getEstadoColor(estado);
                } else {
                    alert('Error al actualizar el estado del pedido');
                }
            });
        }

        function verDetallesPedido(id) {
            // Aquí deberías cargar los detalles del pedido desde el servidor
            document.getElementById('detallesPedidoModal').style.display = 'block';
        }

        function eliminarPedido(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
                fetch(`/Tienda_SNKRS/public/admin/pedidos/eliminar/${id}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el pedido');
                    }
                });
            }
        }

        // Cerrar modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('detallesPedidoModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target == document.getElementById('detallesPedidoModal')) {
                document.getElementById('detallesPedidoModal').style.display = 'none';
            }
        }

        // Colorear los selects de estado al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.estado-pedido').forEach(select => {
                select.style.backgroundColor = getEstadoColor(select.value);
            });
        });
    </script>

    <?php
    function getEstadoColor($estado) {
        $colores = [
            'pendiente' => '#f1c40f',
            'procesando' => '#3498db',
            'enviado' => '#2ecc71',
            'entregado' => '#27ae60',
            'cancelado' => '#e74c3c'
        ];
        return $colores[$estado] ?? '#95a5a6';
    }
    ?>
</body>
</html> 