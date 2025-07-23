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
                    <?php if (isset($_SESSION['user_id'])): ?>
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
            <p><strong>Nombre:</strong> ANGEL</p>
            <p><strong>Correo:</strong> angelgalvan82@gmail.com</p>
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
                <!-- Ejemplo de dirección -->
                <tr>
                    <td>Av. Principal</td>
                    <td>123</td>
                    <td>Centro</td>
                    <td>CDMX</td>
                    <td>CDMX</td>
                    <td>01000</td>
                    <td>Entre calle A y B</td>
                    <td>
                        <button class="perfil-btn">Editar</button>
                        <button class="perfil-btn" style="background:#c00;">Eliminar</button>
                    </td>
                </tr>
                <!-- Más direcciones aquí -->
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
                <!-- Ejemplo de pedido -->
                <tr>
                    <td>1001</td>
                    <td>2025-07-18</td>
                    <td>$1,200.00</td>
                    <td>Entregado</td>
                    <td>
                        <button class="perfil-btn">Ver detalles</button>
                    </td>
                </tr>
                <!-- Más pedidos aquí -->
            </tbody>
        </table>
    </section>

</div>
<footer>
    <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
</footer>
</body>
</html> 