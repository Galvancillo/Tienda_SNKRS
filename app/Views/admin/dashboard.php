<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="/Tienda_SNKRS/public/assets/img/logo.png">
    <title>Dashboard - SNKRS</title>
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
                    <li class="active">
                        <a href="/Tienda_SNKRS/public/admin"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li>
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
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Administrador'); ?></span>
                    <img src="https://via.placeholder.com/40" alt="Admin Avatar" class="avatar">
                </div>
            </header>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-shoe-prints"></i>
                    <div class="stat-info">
                        <h3>Total Productos</h3>
                        <p><?php echo number_format($totalProductos); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-info">
                        <h3>Usuarios</h3>
                        <p><?php echo number_format($totalUsuarios); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="stat-info">
                        <h3>Pedidos</h3>
                        <p><?php echo number_format($totalPedidos); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <div class="stat-info">
                        <h3>Ventas</h3>
                        <p>$<?php echo number_format($totalVentas, 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="recent-activity">
                <h2>Actividad Reciente</h2>
                <div class="activity-list">
                    <?php if (empty($actividades)): ?>
                        <div class="no-activity">
                            <p>No hay actividad reciente para mostrar.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($actividades as $actividad): 
                            switch($actividad['tipo']) {
                                case 'producto':
                                    $icono = 'fas fa-plus-circle';
                                    break;
                                case 'pedido':
                                    $icono = 'fas fa-shopping-cart';
                                    break;
                                case 'usuario':
                                    $icono = 'fas fa-user-plus';
                                    break;
                                default:
                                    $icono = 'fas fa-info-circle';
                            }
                        ?>
                            <div class="activity-item">
                                <i class="<?php echo $icono; ?>"></i>
                                <div class="activity-info">
                                    <p><?php echo $actividad['mensaje']; ?></p>
                                    <span><?php 
                                        $fecha = new DateTime($actividad['fecha']);
                                        $ahora = new DateTime();
                                        $diff = $fecha->diff($ahora);
                                        
                                        if ($diff->d > 0) {
                                            echo "Hace {$diff->d} días";
                                        } elseif ($diff->h > 0) {
                                            echo "Hace {$diff->h} horas";
                                        } else {
                                            echo "Hace {$diff->i} minutos";
                                        }
                                    ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Marcar la página actual en el menú
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.admin-nav li');
            
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                if (link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html> 