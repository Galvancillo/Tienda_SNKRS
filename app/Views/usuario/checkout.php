<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Selecciona dirección de envío - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <style>
        .checkout-container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 24px; }
        .direccion-list { margin-bottom: 24px; }
        .direccion-item { border: 1.5px solid #eee; border-radius: 8px; padding: 18px; margin-bottom: 12px; display: flex; align-items: center; gap: 18px; }
        .direccion-radio { margin-right: 12px; }
        .add-btn { background: #2d8f2d; color: #fff; border: none; border-radius: 6px; padding: 7px 18px; font-size: 1em; cursor: pointer; margin-bottom: 18px; }
        .add-btn:hover { background: #228b22; }
        .continue-btn { background: #111; color: #fff; border: none; border-radius: 6px; padding: 12px 28px; font-size: 1.1em; cursor: pointer; margin-top: 18px; }
        .continue-btn:hover { background: #222; }

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
<div class="checkout-container">
    <h2>Selecciona una dirección de envío</h2>
    <button class="add-btn" onclick="abrirDireccionModal()">Agregar nueva dirección</button>
    <form id="direccionSelectForm" method="post" action="/Tienda_SNKRS/public/usuario/confirmarPedido">
        <div class="direccion-list">
            <?php if (empty($direcciones)): ?>
                <p style="color:#c00;">No tienes direcciones guardadas. Agrega una para continuar.</p>
            <?php else: ?>
                <?php foreach ($direcciones as $dir): ?>
                    <div class="direccion-item">
                        <input type="radio" name="direccion_id" class="direccion-radio" value="<?= $dir['id'] ?>" required>
                        <div>
                            <strong><?= htmlspecialchars($dir['calle']) ?> #<?= htmlspecialchars($dir['numero']) ?></strong><br>
                            <?= htmlspecialchars($dir['colonia']) ?>, <?= htmlspecialchars($dir['ciudad']) ?>, <?= htmlspecialchars($dir['estado']) ?><br>
                            CP: <?= htmlspecialchars($dir['cp']) ?><br>
                            <small><?= htmlspecialchars($dir['referencias']) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="submit" class="continue-btn" <?= empty($direcciones) ? 'disabled' : '' ?>>Continuar con esta dirección</button>
    </form>
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
<script>
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
};
document.getElementById('direccionForm').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    fetch('/Tienda_SNKRS/public/usuario/guardarDireccion', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.success) {
            alert('Dirección guardada correctamente');
            location.reload();
        } else {
            alert('Error: ' + (resp.error || 'No se pudo guardar'));
        }
    })
    .catch(() => alert('Error de red al guardar la dirección.'));
};
</script>
</body>
</html> 