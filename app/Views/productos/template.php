<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Productos'; ?> - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/productos.css">
    <?php if (isset($css_adicional)): ?>
        <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/<?php echo $css_adicional; ?>.css">
    <?php endif; ?>
    
</head>
<body>
    <!-- Contenedor principal -->
    <div class="main-container">
        <nav class="navbar">
            <div class="navbar-content">
                <a href="/Tienda_SNKRS/public/" class="logo-link">
                    <img src="/Tienda_SNKRS/public/assets/img/logo.png" alt="Logo" class="logo-img">
                    <span class="brand">SNKRS WORLD</span>
                </a>
                <ul class="nav-menu">
                    <li><a href="/Tienda_SNKRS/public/productos/nuevo">Lo Nuevo</a></li>
                    <li><a href="/Tienda_SNKRS/public/productos/hombre">Hombre</a></li>
                    <li><a href="/Tienda_SNKRS/public/productos/mujer">Mujer</a></li>
                    <li><a href="/Tienda_SNKRS/public/productos/ninos">Niño/a</a></li>
                    <li><a href="/Tienda_SNKRS/public/productos/ofertas">Ofertas</a></li>
                    <li><a href="/Tienda_SNKRS/public/productos/snkrs">SNKRS</a></li>
                </ul>
                <div class="nav-right">
                    <div class="search-box">
                        <span class="icon">🔍</span>
                        <input type="text" placeholder="Buscar">
                    </div>
                    <span class="icon">🛒</span>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/Tienda_SNKRS/public/logout" class="login-btn">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="/Tienda_SNKRS/public/login" class="login-btn">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <main class="productos-container">
            <h1 class="categoria-titulo"><?php echo $titulo ?? 'Productos'; ?></h1>
            
            <?php if (empty($productos)): ?>
                <p class="no-productos">No hay productos disponibles en esta categoría.</p>
            <?php else: ?>
                <div class="productos-grid">
                    <?php foreach ($productos as $producto): ?>
                        <div class="producto-card">
                            <img src="<?php echo $producto['imagen_url'] ?? 'https://via.placeholder.com/200'; ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                 class="producto-img">
                            <h3 class="producto-nombre"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                            <button class="comprar-btn">Comprar</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
    </footer>
</body>
</html> 