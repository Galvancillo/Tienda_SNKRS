<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión / Registrarse</title>
    <link rel="stylesheet" href="css/Estilos.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="login-body">
    <div class="container">
        <div class="tabs">
            <div class="tab active" id="loginTab">Iniciar Sesión</div>
            <div class="tab" id="registerTab">Registrarse</div>
        </div>
        <form id="loginForm" class="active">
            <div class="form-group">
                <label for="loginEmail">Correo electrónico</label>
                <input type="email" id="loginEmail" name="loginEmail" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Contraseña</label>
                <input type="password" id="loginPassword" name="loginPassword" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <form id="registerForm">
            <div class="form-group">
                <label for="registerEmail">Correo electrónico</label>
                <input type="email" id="registerEmail" name="registerEmail" required>
            </div>
            <div class="form-group">
                <label for="registerPassword">Contraseña</label>
                <input type="password" id="registerPassword" name="registerPassword" required>
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