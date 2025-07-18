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
                    <a href="/Tienda_SNKRS/public/productos/carrito" class="icon" title="Ver carrito">🛒</a>
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
            <?php if (isset($esCarrito) && $esCarrito): ?>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; min-height: 350px;">
                    <div style="flex: 1; padding: 40px;">
                        <h2 style="font-size:2.5rem; margin-bottom: 1rem;">Bolsa de compra</h2>
                        <p style="font-size:1.2rem; color:#333;">No hay productos en tu bolsa de compra.</p>
                    </div>
                    <div style="flex: 1; max-width: 400px; padding: 40px; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
                        <h2 style="font-size:2rem; margin-bottom: 1rem;">Resumen</h2>
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span>Subtotal <span title="Suma de productos en la bolsa">&#9432;</span></span>
                                <span>—</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                <span>Gastos de envío y gestión estimados</span>
                                <span>Gratis</span>
                            </div>
                        </div>
                        <hr>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-weight: bold; margin: 1.5rem 0;">
                            <span>Total</span>
                            <span>—</span>
                        </div>
                        <button style="width:100%; background:#eee; color:#aaa; border:none; border-radius:30px; padding:1rem; font-size:1.1rem; margin-bottom:1rem; cursor:not-allowed;">Pagar con tarjeta</button>
                        <button style="width:100%; background:#fafafa; color:#888; border:1px solid #eee; border-radius:30px; padding:1rem; font-size:1.1rem; font-style:italic; cursor:not-allowed;">PayPal</button>
                    </div>
                </div>
            <?php elseif (empty($productos)): ?>
                <p class="no-productos">No hay productos disponibles en esta categoría.</p>
            <?php else: ?>
                <div class="productos-grid">
                    <?php foreach ($productos as $producto): ?>
                        <div class="producto-card" data-id="<?php echo $producto['id']; ?>">
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

    <!-- Modal de vista rápida de producto -->
    <div id="modalProducto" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
        <div class="modal-content" style="background:#fff; margin:40px auto; padding:30px; border-radius:16px; max-width:600px; position:relative;">
            <span class="close" id="cerrarModalProducto" style="position:absolute; top:10px; right:20px; font-size:2rem; cursor:pointer;">&times;</span>
            <div id="modalProductoBody">
                <!-- Aquí se carga la info del producto -->
            </div>
        </div>
    </div>
    <script>
    // Función para abrir el modal de producto
    function abrirModalProducto(id) {
        fetch(`/Tienda_SNKRS/public/productos/detalle/${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) { alert('Error al cargar producto'); return; }
                const p = data.producto;
                let tallasHtml = '';
                if (p.tallas && p.tallas.length > 0) {
                    tallasHtml = '<label for="modalTalla">Talla:</label> <select id="modalTalla">';
                    p.tallas.forEach(t => {
                        if (t.stock > 0) {
                            tallasHtml += `<option value="${t.id}">${t.talla} (${t.stock} disponibles)</option>`;
                        }
                    });
                    tallasHtml += '</select>';
                } else {
                    tallasHtml = '<span style="color:#c00">Sin tallas disponibles</span>';
                }
                document.getElementById('modalProductoBody').innerHTML = `
                    <div style='display:flex; gap:30px;'>
                        <img src='${p.imagen_url ?? 'https://via.placeholder.com/250'}' alt='${p.nombre}' style='width:220px; height:220px; object-fit:cover; border-radius:10px;'>
                        <div style='flex:1;'>
                            <h2 style='margin-top:0;'>${p.nombre}</h2>
                            <p style='color:#555;'>${p.descripcion ?? ''}</p>
                            <div style='font-size:1.3rem; font-weight:bold; margin:10px 0;'>$${parseFloat(p.precio).toFixed(2)}</div>
                            ${tallasHtml}
                            <div style='margin:15px 0;'>
                                <label for="modalCantidad">Cantidad:</label>
                                <input type="number" id="modalCantidad" min="1" value="1" style="width:60px;">
                            </div>
                            <button id="btnAgregarAlCarrito" style="background:#111; color:#fff; border:none; border-radius:8px; padding:10px 30px; font-size:1.1rem; cursor:pointer;">Agregar al carrito</button>
                        </div>
                    </div>
                `;
                document.getElementById('modalProducto').style.display = 'block';
                document.getElementById('btnAgregarAlCarrito').onclick = function() {
                    const id_talla = document.getElementById('modalTalla') ? document.getElementById('modalTalla').value : null;
                    const cantidad = document.getElementById('modalCantidad').value;
                    fetch('/Tienda_SNKRS/public/productos/agregar-al-carrito', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_producto=${encodeURIComponent(p.id)}&id_talla=${encodeURIComponent(id_talla)}&cantidad=${encodeURIComponent(cantidad)}`
                    })
                    .then(res => res.json())
                    .then(resp => {
                        if (resp.success) {
                            alert('Producto agregado al carrito');
                            document.getElementById('modalProducto').style.display = 'none';
                        } else {
                            alert(resp.error || 'Error al agregar al carrito');
                        }
                    });
                };
            });
    }
    // Cerrar modal
    document.getElementById('cerrarModalProducto').onclick = function() {
        document.getElementById('modalProducto').style.display = 'none';
    };
    window.onclick = function(event) {
        if (event.target == document.getElementById('modalProducto')) {
            document.getElementById('modalProducto').style.display = 'none';
        }
    };
    // Reemplazar botones Comprar
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.comprar-btn').forEach(btn => {
            const card = btn.closest('.producto-card');
            if (card) {
                const id = card.querySelector('img').src.match(/([a-f0-9]{40})\./) ? card.querySelector('img').src.match(/([a-f0-9]{40})\./)[1] : null;
                btn.onclick = function(e) {
                    e.preventDefault();
                    // Mejor: usar data-id en el botón o en el producto
                    const idProd = card.getAttribute('data-id') || card.querySelector('img').getAttribute('data-id') || btn.getAttribute('data-id') || btn.dataset.id;
                    abrirModalProducto(idProd || btn.value || btn.getAttribute('value'));
                };
            }
        });
    });
    </script>
    <!-- Footer -->
    <footer>
        <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
    </footer>
</body>
</html> 