<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <style>
        .perfil-main-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .perfil-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .perfil-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .perfil-section h2 {
            margin-top: 0;
            font-size: 1.4em;
            color: #222;
        }
        .perfil-datos, .perfil-direcciones, .perfil-pedidos {
            margin-top: 16px;
        }
        .perfil-direcciones-list, .perfil-pedidos-list {
            width: 100%;
            border-collapse: collapse;
        }
        .perfil-direcciones-list th, .perfil-direcciones-list td,
        .perfil-pedidos-list th, .perfil-pedidos-list td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .perfil-direcciones-list th, .perfil-pedidos-list th {
            background: #f7f7f7;
        }
        .perfil-direcciones-list tr:last-child td,
        .perfil-pedidos-list tr:last-child td {
            border-bottom: none;
        }
        .perfil-btn {
            background: #111;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 18px;
            font-size: 1em;
            cursor: pointer;
            margin-right: 8px;
        }
        .perfil-btn:hover {
            background: #222;
        }
        .perfil-add-btn {
            background: #2d8f2d;
            margin-bottom: 10px;
        }
        /* Estilos mejorados para el modal de dirección */
        #direccionModal .modal-content form div {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
        }
        #direccionModal .modal-content label {
            font-weight: 500;
            margin-bottom: 4px;
            color: #222;
        }
        #direccionModal .modal-content input[type="text"] {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            background: #fafafa;
            transition: border 0.2s;
        }
        #direccionModal .modal-content input[type="text"]:focus {
            border: 1.5px solid #111;
            outline: none;
            background: #fff;
        }
        #direccionModal .modal-content button[type="submit"] {
            width: 100%;
            margin-top: 18px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        #direccionModal .modal-content button[type="submit"]:hover {
            background: #222;
        }
    </style>
</head>
<body>
    <!-- Navbar reutilizado -->
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
<div class="perfil-main-container">

    <!-- Sección 1: Datos de la cuenta -->
    <section class="perfil-section">
        <h2>Datos de la cuenta</h2>
        <div class="perfil-datos">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
            <button class="perfil-btn">Editar datos</button>
            <a href="/Tienda_SNKRS/public/logout" class="perfil-btn" style="background:#c00; float:right;">Cerrar sesión</a>
        </div>
    </section>

    <!-- Sección 2: Direcciones de entrega -->
    <section class="perfil-section">
        <h2>Direcciones de entrega</h2>
        <button class="perfil-btn perfil-add-btn">Agregar nueva dirección</button>
        <table class="perfil-direcciones-list">
            <thead>
                <tr>
                    <th>Calle</th>
                    <th>Número</th>
                    <th>Colonia</th>
                    <th>Ciudad</th>
                    <th>Estado</th>
                    <th>CP</th>
                    <th>Referencias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($direcciones)): ?>
                    <tr><td colspan="8" style="text-align:center; color:#888;">No tienes direcciones guardadas.</td></tr>
                <?php else: ?>
                    <?php foreach ($direcciones as $dir): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dir['calle']); ?></td>
                            <td><?php echo htmlspecialchars($dir['numero']); ?></td>
                            <td><?php echo htmlspecialchars($dir['colonia']); ?></td>
                            <td><?php echo htmlspecialchars($dir['ciudad']); ?></td>
                            <td><?php echo htmlspecialchars($dir['estado']); ?></td>
                            <td><?php echo htmlspecialchars($dir['cp']); ?></td>
                            <td><?php echo htmlspecialchars($dir['referencias']); ?></td>
                            <td>
                                <button class="perfil-btn">Editar</button>
                                <button class="perfil-btn" style="background:#c00;">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <!-- Sección 3: Historial de pedidos -->
    <section class="perfil-section">
        <h2>Historial de pedidos</h2>
        <table class="perfil-pedidos-list">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr><td colspan="5" style="text-align:center; color:#888;">No tienes pedidos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($pedido['fecha_pedido']))); ?></td>
                            <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></td>
                            <td>
                                <button class="perfil-btn">Ver detalles</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</div>
<!-- Modal para agregar/editar dirección -->
<div id="direccionModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div class="modal-content" style="background:#fff; margin:40px auto; padding:30px; border-radius:16px; max-width:500px; position:relative;">
        <span class="close" id="cerrarDireccionModal" style="position:absolute; top:10px; right:20px; font-size:2rem; cursor:pointer;">&times;</span>
        <h2 id="direccionModalTitle">Agregar dirección</h2>
        <form id="direccionForm">
            <input type="hidden" id="direccionId" name="id">
            <div>
                <label>Calle:</label>
                <input type="text" name="calle" id="calle" required>
            </div>
            <div>
                <label>Número:</label>
                <input type="text" name="numero" id="numero" required>
            </div>
            <div>
                <label>Colonia:</label>
                <input type="text" name="colonia" id="colonia" required>
            </div>
            <div>
                <label>Ciudad:</label>
                <input type="text" name="ciudad" id="ciudad" required>
            </div>
            <div>
                <label>Estado:</label>
                <input type="text" name="estado" id="estado" required>
            </div>
            <div>
                <label>CP:</label>
                <input type="text" name="cp" id="cp" required>
            </div>
            <div>
                <label>Referencias:</label>
                <input type="text" name="referencias" id="referencias">
            </div>
            <button type="submit" class="perfil-btn" style="margin-top:10px;">Guardar</button>
        </form>
    </div>
</div>

<!-- Modal para detalles de pedido -->
<div id="pedidoModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div class="modal-content" style="background:#fff; margin:40px auto; padding:30px; border-radius:16px; max-width:600px; position:relative;">
        <span class="close" id="cerrarPedidoModal" style="position:absolute; top:10px; right:20px; font-size:2rem; cursor:pointer;">&times;</span>
        <h2>Detalles del pedido</h2>
        <div id="pedidoDetallesBody">
            <!-- Aquí se cargan los detalles del pedido -->
        </div>
    </div>
</div>

<script>
// MODAL DIRECCIÓN
function abrirDireccionModal(direccion = null) {
    document.getElementById('direccionModalTitle').textContent = direccion ? 'Editar dirección' : 'Agregar dirección';
    document.getElementById('direccionForm').reset();
    document.getElementById('direccionId').value = direccion ? direccion.id : '';
    document.getElementById('calle').value = direccion ? direccion.calle : '';
    document.getElementById('numero').value = direccion ? direccion.numero : '';
    document.getElementById('colonia').value = direccion ? direccion.colonia : '';
    document.getElementById('ciudad').value = direccion ? direccion.ciudad : '';
    document.getElementById('estado').value = direccion ? direccion.estado : '';
    document.getElementById('cp').value = direccion ? direccion.cp : '';
    document.getElementById('referencias').value = direccion ? direccion.referencias : '';
    document.getElementById('direccionModal').style.display = 'block';
}
document.getElementById('cerrarDireccionModal').onclick = function() {
    document.getElementById('direccionModal').style.display = 'none';
};
window.onclick = function(event) {
    if (event.target == document.getElementById('direccionModal')) {
        document.getElementById('direccionModal').style.display = 'none';
    }
    if (event.target == document.getElementById('pedidoModal')) {
        document.getElementById('pedidoModal').style.display = 'none';
    }
};

// MODAL PEDIDO
function abrirPedidoModal(pedidoId) {
    // Aquí luego haremos el fetch al backend para cargar los detalles
    document.getElementById('pedidoDetallesBody').innerHTML = '<p>Cargando detalles...</p>';
    document.getElementById('pedidoModal').style.display = 'block';
    // Ejemplo de fetch (lo implementaremos después)
    // fetch(`/Tienda_SNKRS/public/usuario/pedido/${pedidoId}`)
    //   .then(res => res.json())
    //   .then(data => { ... });
}
document.getElementById('cerrarPedidoModal').onclick = function() {
    document.getElementById('pedidoModal').style.display = 'none';
};

// Asignar eventos a los botones (esto es solo para la demo visual, luego se hará dinámico)
document.querySelector('.perfil-add-btn').onclick = function() {
    abrirDireccionModal();
};
document.querySelectorAll('.perfil-direcciones-list .perfil-btn:not([style])').forEach(btn => {
    btn.onclick = function() {
        // Aquí deberías pasar los datos reales de la dirección
        abrirDireccionModal({
            id: 1,
            calle: 'Av. Principal',
            numero: '123',
            colonia: 'Centro',
            ciudad: 'CDMX',
            estado: 'CDMX',
            cp: '01000',
            referencias: 'Entre calle A y B'
        });
    };
});
document.querySelectorAll('.perfil-pedidos-list .perfil-btn').forEach(btn => {
    btn.onclick = function() {
        abrirPedidoModal(1001); // Aquí deberías pasar el ID real del pedido
    };
});
</script>
<footer>
    <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
</footer>
</body>
</html> 