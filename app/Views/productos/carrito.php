<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="/Tienda_SNKRS/public/assets/img/logo.png">
    <title><?php echo $titulo; ?> - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/productos.css">
    <?php if (isset($css_adicional)): ?>
        <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/<?php echo $css_adicional; ?>.css">
    <?php endif; ?>
    <style>
.carrito-container {
    max-width: 900px;
    margin: 40px auto 60px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 32px 24px 24px 24px;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.carrito-container h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 28px;
    color: #111;
    letter-spacing: 1px;
}
.carrito-tabla {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 16px;
    margin-bottom: 32px;
}
.carrito-tabla th {
    background: #f5f5f5;
    color: #222;
    font-weight: 600;
    padding: 12px 8px;
    border-radius: 8px 8px 0 0;
    font-size: 1.05rem;
}
.carrito-tabla td {
    background: #fafbfc;
    padding: 18px 10px;
    vertical-align: middle;
    border-radius: 0 0 8px 8px;
    font-size: 1.08rem;
    color: #222;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.carrito-tabla img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    margin-right: 18px;
    border: 1px solid #eee;
    background: #fff;
}
.carrito-producto {
    display: flex;
    align-items: center;
    gap: 12px;
}
.carrito-eliminar {
    background: #fff;
    color: #c00;
    border: 1px solid #eee;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.carrito-eliminar:hover {
    background: #fbe9e7;
    color: #a00;
}
.carrito-total {
    text-align: right;
    font-size: 1.3rem;
    font-weight: 700;
    color: #111;
    margin-bottom: 18px;
}
.carrito-resumen {
    background: #f7f7f7;
    border-radius: 14px;
    padding: 24px 28px;
    max-width: 350px;
    margin-left: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.carrito-resumen h2 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 18px;
    color: #222;
}
.carrito-resumen .resumen-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 1.08rem;
}
.carrito-finalizar {
    width: 100%;
    background: #111;
    color: #fff;
    border: none;
    border-radius: 30px;
    padding: 16px 0;
    font-size: 1.15rem;
    font-weight: 600;
    margin-top: 18px;
    cursor: pointer;
    transition: background 0.2s;
    letter-spacing: 1px;
}
.carrito-finalizar:hover {
    background: #222;
}
.carrito-cantidad-eliminar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    min-width: 140px;
    height: 48px;
}
.carrito-cantidad-eliminar form {
    display: flex;
    align-items: center;
    margin: 0;
}
.carrito-cantidad-eliminar button.carrito-eliminar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #eee;
    background: #fff;
    color: #c00;
    font-size: 1.3rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    margin: 0;
    padding: 0;
}
.carrito-cantidad-eliminar button.carrito-eliminar:hover {
    background: #fbe9e7;
    color: #a00;
}
.carrito-cantidad-eliminar span {
    display: inline-block;
    min-width: 32px;
    text-align: center;
    font-size: 1.15rem;
    font-weight: 500;
    letter-spacing: 1px;
    margin: 0 4px;
}
.btn-volver {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: #111;
    border: 1px solid #eee;
    border-radius: 30px;
    padding: 8px 18px;
    font-size: 1.05rem;
    font-weight: 500;
    margin-bottom: 18px;
    margin-top: 18px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: background 0.2s, color 0.2s;
    text-decoration: none;
}
.btn-volver:hover {
    background: #f5f5f5;
    color: #0071e3;
}
@media (max-width: 700px) {
    .carrito-container { padding: 10px; }
    .carrito-resumen { max-width: 100%; margin: 24px 0 0 0; }
    .carrito-tabla img { width: 60px; height: 60px; }
}
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
    <main class="carrito-container">
        <a href="javascript:history.back()" class="btn-volver">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Volver
        </a>
        <h1><?php echo $titulo; ?></h1>
        <?php if (!empty($productos)): ?>
        <div style="overflow-x:auto;">
        <table class="carrito-tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($productos as $item): ?>
                    <tr>
                        <td>
                            <div class="carrito-producto">
                                <img src="<?php echo $item['imagen_url']; ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                                <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                            </div>
                        </td>
                        <td style="text-align:center;"><?php echo htmlspecialchars($item['talla']); ?></td>
                        <td style="text-align:center;">
                            <div class="carrito-cantidad-eliminar">
                                <?php if ($item['cantidad'] > 1): ?>
                                    <form method="post" action="/Tienda_SNKRS/public/productos/carrito?restar=<?php echo $item['detalle_id']; ?>" style="display:inline;">
                                        <input type="hidden" name="cantidad_cambio" value="-1">
                                        <button type="submit" class="carrito-eliminar" title="Restar uno" style="font-size:1.3rem;">-</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="/Tienda_SNKRS/public/productos/carrito?restar=<?php echo $item['detalle_id']; ?>" style="display:inline;">
                                        <input type="hidden" name="cantidad_cambio" value="-1">
                                        <button type="submit" class="carrito-eliminar" title="Eliminar producto" style="font-size:1.3rem; padding:0 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <span style="display:inline-block; min-width:32px; text-align:center; font-size:1.15rem; font-weight:500; letter-spacing:1px; margin:0 4px;"><?php echo $item['cantidad']; ?></span>
                                <form method="post" action="/Tienda_SNKRS/public/productos/carrito?sumar=<?php echo $item['detalle_id']; ?>" style="display:inline;">
                                    <input type="hidden" name="cantidad_cambio" value="1">
                                    <button type="submit" class="carrito-eliminar" title="Sumar uno" style="font-size:1.3rem;">+</button>
                                </form>
                            </div>
                        </td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        <td style="text-align:center;"></td>
                        <?php $total += $item['precio'] * $item['cantidad']; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="carrito-resumen">
            <h2>Resumen de compra</h2>
            <div class="resumen-row">
                <span>Subtotal</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="resumen-row">
                <span>Envío</span>
                <span>Gratis</span>
            </div>
            <hr style="margin: 12px 0; border: none; border-top: 1px solid #eee;">
            <div class="resumen-row" style="font-weight:700; font-size:1.15rem;">
                <span>Total</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            <button class="carrito-finalizar" disabled>Finalizar compra</button>
            <div style="text-align:center; color:#888; font-size:0.98rem; margin-top:8px;">(Funcionalidad próximamente)</div>
        </div>
    <?php else: ?>
        <p style="text-align:center; color:#c00; font-size:1.2rem; margin:40px 0;">No hay productos en tu carrito.</p>
    <?php endif; ?>
</main>
</div>
</body>
</html> 