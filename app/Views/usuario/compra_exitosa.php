<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Compra exitosa! - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <style>
        .exito-container { max-width: 700px; margin: 60px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 24px; text-align: center; }
        .exito-container h2 { color: #2d8f2d; font-size: 2.1rem; margin-bottom: 18px; }
        .exito-container .pedido-id { color: #888; margin-bottom: 18px; }
        .exito-container .resumen { margin: 30px 0 0 0; text-align: left; }
        .exito-container .resumen h3 { font-size: 1.2rem; margin-bottom: 10px; }
        .exito-container .producto { display: flex; align-items: center; gap: 18px; margin-bottom: 14px; }
        .exito-container .producto img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; border: 1px solid #eee; }
        .exito-container .producto-info { font-size: 1.05rem; }
        .exito-container .total { font-weight: bold; font-size: 1.15rem; margin-top: 18px; }
        .exito-container .volver-btn { margin-top: 30px; background: #111; color: #fff; border: none; border-radius: 7px; padding: 12px 28px; font-size: 1.1em; cursor: pointer; }
        .exito-container .volver-btn:hover { background: #222; }
    </style>
</head>
<body>
<div class="main-container">
    <nav class="navbar">
        <div class="navbar-content">
            <a href="/Tienda_SNKRS/public/productos/nuevo" class="logo-link">
                <img src="/Tienda_SNKRS/public/assets/img/logo.png" alt="Logo" class="logo-img">
                <span class="brand">SNKRS WORLD</span>
            </a>
            <ul class="nav-menu">
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
                <a href="/Tienda_SNKRS/public/productos/carrito" class="icon" title="Ver carrito">🛒</a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="/Tienda_SNKRS/public/usuario/perfil" class="icon" title="Editar Perfil">👤</a>
                <?php else: ?>
                    <a href="/Tienda_SNKRS/public/login" class="icon" title="Iniciar Sesión">👤</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>
<div class="exito-container">
    <h2>¡Gracias por tu compra!</h2>
    <div class="pedido-id">Pedido #<?= htmlspecialchars($pedido['id']) ?> realizado correctamente.</div>
    <div class="resumen">
        <h3>Resumen de tu pedido:</h3>
        <?php $total = 0; ?>
        <?php foreach ($detalles as $item): ?>
            <div class="producto">
                <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                <div class="producto-info">
                    <strong><?= htmlspecialchars($item['nombre']) ?></strong><br>
                    Talla: <?= htmlspecialchars($item['talla']) ?> | Cantidad: <?= htmlspecialchars($item['cantidad']) ?><br>
                    $<?= number_format($item['precio_unitario'], 2) ?> c/u
                </div>
            </div>
            <?php $total += $item['precio_unitario'] * $item['cantidad']; ?>
        <?php endforeach; ?>
        <div class="total">Total pagado: $<?= number_format($total, 2) ?></div>
    </div>
    <a href="/Tienda_SNKRS/public/productos/hombre"><button class="volver-btn">Seguir comprando</button></a>
</div>
</body>
</html> 