<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago con tarjeta - SNKRS</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <style>
        .pago-container { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 24px; }
        .pago-container h2 { margin-bottom: 24px; }
        .pago-form label { font-weight: 500; color: #222; margin-bottom: 4px; }
        .pago-form input { width: 100%; padding: 10px; border: 1.5px solid #ccc; border-radius: 7px; font-size: 1em; background: #fafafa; margin-bottom: 16px; transition: border 0.2s; }
        .pago-form input:focus { border: 1.5px solid #111; background: #fff; outline: none; }
        .pago-btn { width: 100%; background: #111; color: #fff; border: none; border-radius: 7px; padding: 13px 0; font-size: 1.1em; cursor: pointer; margin-top: 10px; }
        .pago-btn:hover { background: #222; }
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
<div class="pago-container">
    <h2>Pago con tarjeta</h2>
    <form class="pago-form" method="post" action="/Tienda_SNKRS/public/usuario/finalizarPago">
        <input type="hidden" name="direccion_id" value="<?= htmlspecialchars($direccion_id) ?>">
        <div>
            <label>Nombre en la tarjeta:</label>
            <input type="text" name="nombre_tarjeta" required>
        </div>
        <div>
            <label>Número de tarjeta:</label>
            <input type="text" name="numero_tarjeta" maxlength="19" pattern="\d{16,19}" required>
        </div>
        <div style="display:flex; gap:12px;">
            <div style="flex:1;">
                <label>Fecha de expiración:</label>
                <input type="text" name="expiracion" placeholder="MM/AA" maxlength="5" pattern="\d{2}/\d{2}" required>
            </div>
            <div style="flex:1;">
                <label>CVV:</label>
                <input type="text" name="cvv" maxlength="4" pattern="\d{3,4}" required>
            </div>
        </div>
        <button type="submit" class="pago-btn">Pagar</button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expInput = document.querySelector('input[name="expiracion"]');
    const form = document.querySelector('.pago-form');
    const cvvInput = document.querySelector('input[name="cvv"]');
    const numInput = document.querySelector('input[name="numero_tarjeta"]');

    expInput.addEventListener('input', function(e) {
        let value = expInput.value.replace(/[^0-9]/g, '');
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        expInput.value = value;
    });

    form.onsubmit = function(e) {
        // Validación de fecha de expiración
        const expVal = expInput.value;
        const expMatch = expVal.match(/^(\d{2})\/(\d{2})$/);
        if (!expMatch) {
            alert('La fecha de expiración debe tener el formato MM/AA.');
            expInput.focus();
            e.preventDefault();
            return false;
        }
        const mes = parseInt(expMatch[1], 10);
        const anio = parseInt('20' + expMatch[2], 10);
        const hoy = new Date();
        const mesActual = hoy.getMonth() + 1;
        const anioActual = hoy.getFullYear();
        if (mes < 1 || mes > 12) {
            alert('El mes de expiración debe estar entre 01 y 12.');
            expInput.focus();
            e.preventDefault();
            return false;
        }
        if (anio < anioActual || (anio === anioActual && mes < mesActual)) {
            alert('La fecha de expiración no puede ser menor a la fecha actual.');
            expInput.focus();
            e.preventDefault();
            return false;
        }
        // Validación de número de tarjeta (opcional, solo longitud)
        const numVal = numInput.value.replace(/\s+/g, '');
        if (numVal.length < 16 || numVal.length > 19) {
            alert('El número de tarjeta debe tener entre 16 y 19 dígitos.');
            numInput.focus();
            e.preventDefault();
            return false;
        }
        // Validación de CVV
        if (!/^\d{3,4}$/.test(cvvInput.value)) {
            alert('El CVV debe tener 3 o 4 dígitos.');
            cvvInput.focus();
            e.preventDefault();
            return false;
        }
    };
});
</script>
</body>
</html> 