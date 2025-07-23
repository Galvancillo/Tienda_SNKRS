<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <link rel="icon" type="image/x-icon" href="/Tienda_SNKRS/public/assets/img/favicon.ico">
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
        /* Modal de usuario mejorado */
        #usuarioModal .modal-content {
            background: #fff;
            margin: 60px auto;
            padding: 32px 28px 24px 28px;
            border-radius: 18px;
            max-width: 420px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            animation: modalFadeIn 0.3s;
        }
        @keyframes modalFadeIn {
            from { transform: translateY(-40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        #usuarioModal .close {
            position: absolute;
            top: 14px;
            right: 22px;
            font-size: 2.2rem;
            color: #888;
            cursor: pointer;
            transition: color 0.2s;
        }
        #usuarioModal .close:hover {
            color: #c00;
        }
        #usuarioModal form label {
            font-weight: 500;
            margin-bottom: 4px;
            color: #222;
        }
        #usuarioModal form input[type="text"],
        #usuarioModal form input[type="email"],
        #usuarioModal form input[type="password"] {
            padding: 9px 12px;
            border: 1.5px solid #ccc;
            border-radius: 7px;
            font-size: 1em;
            background: #fafafa;
            margin-bottom: 14px;
            transition: border 0.2s;
        }
        #usuarioModal form input:focus {
            border: 1.5px solid #111;
            background: #fff;
            outline: none;
        }
        #usuarioModal .perfil-btn {
            width: 100%;
            margin-top: 10px;
            font-size: 1.08em;
            padding: 11px 0;
        }
        #usuarioModal .password-toggle {
            position: absolute;
            right: 32px;
            top: 60px;
            cursor: pointer;
            font-size: 1.2em;
            color: #888;
            background: none;
            border: none;
        }
        /* Modal de dirección mejorado */
        #direccionModal .modal-content {
            background: #fff;
            margin: 60px auto;
            padding: 32px 28px 24px 28px;
            border-radius: 18px;
            max-width: 500px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            animation: modalFadeIn 0.3s;
        }
        @keyframes modalFadeIn {
            from { transform: translateY(-40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        #direccionModal .close {
            position: absolute;
            top: 14px;
            right: 22px;
            font-size: 2.2rem;
            color: #888;
            cursor: pointer;
            transition: color 0.2s;
        }
        #direccionModal .close:hover {
            color: #c00;
        }
        #direccionModal form label {
            font-weight: 500;
            margin-bottom: 4px;
            color: #222;
        }
        #direccionModal form input[type="text"] {
            padding: 9px 12px;
            border: 1.5px solid #ccc;
            border-radius: 7px;
            font-size: 1em;
            background: #fafafa;
            margin-bottom: 14px;
            transition: border 0.2s;
        }
        #direccionModal form input:focus {
            border: 1.5px solid #111;
            background: #fff;
            outline: none;
        }
        #direccionModal .perfil-btn {
            width: 100%;
            margin-top: 10px;
            font-size: 1.08em;
            padding: 11px 0;
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
                        <tr data-id="<?= $dir['id'] ?>">
                            <td><?php echo htmlspecialchars($dir['calle']); ?></td>
                            <td><?php echo htmlspecialchars($dir['numero']); ?></td>
                            <td><?php echo htmlspecialchars($dir['colonia']); ?></td>
                            <td><?php echo htmlspecialchars($dir['ciudad']); ?></td>
                            <td><?php echo htmlspecialchars($dir['estado']); ?></td>
                            <td><?php echo htmlspecialchars($dir['cp']); ?></td>
                            <td><?php echo htmlspecialchars($dir['referencias']); ?></td>
                            <td>
                                <button class="perfil-btn"
                                    data-id="<?= $dir['id'] ?>"
                                    data-calle="<?= htmlspecialchars($dir['calle'], ENT_QUOTES) ?>"
                                    data-numero="<?= htmlspecialchars($dir['numero'], ENT_QUOTES) ?>"
                                    data-colonia="<?= htmlspecialchars($dir['colonia'], ENT_QUOTES) ?>"
                                    data-ciudad="<?= htmlspecialchars($dir['ciudad'], ENT_QUOTES) ?>"
                                    data-estado="<?= htmlspecialchars($dir['estado'], ENT_QUOTES) ?>"
                                    data-cp="<?= htmlspecialchars($dir['cp'], ENT_QUOTES) ?>"
                                    data-referencias="<?= htmlspecialchars($dir['referencias'], ENT_QUOTES) ?>"
                                >Editar</button>
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
                <input type="text" name="referencias" id="referencias" placeholder="Opcional">
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

<!-- Modal para editar datos de usuario -->
<div id="usuarioModal" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div class="modal-content">
        <span class="close" id="cerrarUsuarioModal">&times;</span>
        <h2 style="margin-bottom:18px;">Editar datos de la cuenta</h2>
        <form id="usuarioForm" autocomplete="off">
            <div>
                <label for="usuarioNombre">Nombre:</label>
                <input type="text" name="nombre" id="usuarioNombre" required>
            </div>
            <div>
                <label for="usuarioCorreo">Correo:</label>
                <input type="email" name="correo" id="usuarioCorreo" required>
            </div>
            <div style="position:relative;">
                <label for="usuarioContrasena">Nueva contraseña:</label>
                <input type="password" name="contraseña" id="usuarioContrasena" autocomplete="new-password">
                <button type="button" class="password-toggle" tabindex="-1" onclick="togglePasswordUsuario()" aria-label="Mostrar/Ocultar contraseña">&#128065;</button>
            </div>
            <button type="submit" class="perfil-btn">Guardar cambios</button>
        </form>
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
    if (event.target == document.getElementById('usuarioModal')) {
        document.getElementById('usuarioModal').style.display = 'none';
    }
};

// --- ENVÍO AJAX DEL FORMULARIO DE DIRECCIÓN ---
document.getElementById('direccionForm').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const isEdit = form.id.value !== '';

    fetch(isEdit ? '/Tienda_SNKRS/public/usuario/editarDireccion' : '/Tienda_SNKRS/public/usuario/guardarDireccion', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            alert(isEdit ? 'Dirección actualizada correctamente' : 'Dirección guardada correctamente');
            location.reload(); // O actualiza la tabla dinámicamente
        } else {
            alert('Error: ' + (resp.error || 'No se pudo guardar'));
        }
    })
    .catch(() => alert('Error de red al guardar la dirección.'));
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
        abrirDireccionModal({
            id: btn.getAttribute('data-id'),
            calle: btn.getAttribute('data-calle'),
            numero: btn.getAttribute('data-numero'),
            colonia: btn.getAttribute('data-colonia'),
            ciudad: btn.getAttribute('data-ciudad'),
            estado: btn.getAttribute('data-estado'),
            cp: btn.getAttribute('data-cp'),
            referencias: btn.getAttribute('data-referencias')
        });
    };
});
document.querySelectorAll('.perfil-pedidos-list .perfil-btn').forEach(btn => {
    btn.onclick = function() {
        const row = btn.closest('tr');
        const pedidoId = row.querySelector('td').textContent.trim(); // Asume que el primer td es el ID
        document.getElementById('pedidoDetallesBody').innerHTML = '<p>Cargando detalles...</p>';
        document.getElementById('pedidoModal').style.display = 'block';
        fetch('/Tienda_SNKRS/public/usuario/pedido-detalle/' + pedidoId)
            .then(res => res.text())
            .then(html => {
                document.getElementById('pedidoDetallesBody').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('pedidoDetallesBody').innerHTML = '<p style="color:#c00;">Error al cargar detalles.</p>';
            });
    };
});

// --- ELIMINAR DIRECCIÓN ---
document.querySelectorAll('.perfil-direcciones-list .perfil-btn[style*="background:#c00"]')
    .forEach(btn => {
        btn.onclick = function() {
            if (!confirm('¿Seguro que deseas eliminar esta dirección?')) return;
            // Obtener el id de la dirección de la fila
            const row = btn.closest('tr');
            // Busca el id en el array PHP renderizado (puedes agregar un data-id en el botón o la fila para mayor robustez)
            // Aquí lo hacemos por índice de fila, pero lo ideal es usar un atributo data-id
            // Suponiendo que el id está oculto en un input o atributo:
            // <tr data-id="<?= $dir['id'] ?>">
            const direccionId = row.getAttribute('data-id');
            if (!direccionId) {
                alert('No se pudo obtener el ID de la dirección.');
                return;
            }
            const formData = new FormData();
            formData.append('id', direccionId);
            fetch('/Tienda_SNKRS/public/usuario/eliminarDireccion', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    row.remove();
                } else {
                    alert('Error: ' + (resp.error || 'No se pudo eliminar la dirección'));
                }
            })
            .catch(() => alert('Error de red al eliminar la dirección.'));
        };
    });

// Abrir modal de editar datos
const editarDatosBtn = document.querySelector('.perfil-datos .perfil-btn');
editarDatosBtn.onclick = function() {
    document.getElementById('usuarioNombre').value = "<?= htmlspecialchars($usuario['nombre'], ENT_QUOTES) ?>";
    document.getElementById('usuarioCorreo').value = "<?= htmlspecialchars($usuario['correo'], ENT_QUOTES) ?>";
    document.getElementById('usuarioContrasena').value = '';
    document.getElementById('usuarioModal').style.display = 'block';
};
document.getElementById('cerrarUsuarioModal').onclick = function() {
    document.getElementById('usuarioModal').style.display = 'none';
};
// Enviar datos por AJAX
const usuarioForm = document.getElementById('usuarioForm');
usuarioForm.onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    fetch('/Tienda_SNKRS/public/usuario/actualizar', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            alert('Datos actualizados correctamente');
            location.reload();
        } else {
            alert('Error: ' + (resp.error || 'No se pudo actualizar'));
        }
    })
    .catch(() => alert('Error de red al actualizar los datos.'));
};

function togglePasswordUsuario() {
    const input = document.getElementById('usuarioContrasena');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
<footer>
    <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
</footer>
</body>
</html> 