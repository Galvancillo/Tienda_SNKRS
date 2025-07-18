<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión / Registrarse</title>
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/Estilos.css">
    <link rel="stylesheet" href="/Tienda_SNKRS/public/assets/css/login.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body class="login-body">
    <div class="container">
        <?php
        // Mostrar mensajes de error o éxito
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <div class="tabs">
            <div class="tab active" id="loginTab">Iniciar Sesión</div>
            <div class="tab" id="registerTab">Registrarse</div>
        </div>
        <form id="loginForm" class="active" action="/Tienda_SNKRS/public/login" method="POST">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <form id="registerForm" action="/Tienda_SNKRS/public/register" method="POST">
            <div class="form-group">
                <label for="registerEmail">Correo electrónico</label>
                <input type="email" id="registerEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="registerUsername">Nombre de usuario</label>
                <input type="text" id="registerUsername" name="username" required>
            </div>
            <div class="form-group">
                <label for="registerPassword">Contraseña</label>
                <input type="password" id="registerPassword" name="password" required>
            </div>
            <button type="submit">Registrarse</button>
        </form>
    </div>
    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });

        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });
    </script>
    <footer class="login-footer">
        <p>© 2025 SNKRS, Inc. Todos los derechos reservados.</p>
    </footer>
</body>
</html> 