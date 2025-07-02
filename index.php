<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/Estilos.css">
    <title>Sneakers World</title>
    <h1 class="titulo">Bienvenido a Sneakers World</h1>
   
    
    
</head>
<body>
    <nav class="navbar">
      <div class="navbar-content">
        <a href="index.php" class="logo-link">
          <img src="img/logo.png" alt="Logo" class="logo-img">
        </a>
        <ul class="nav-menu">
          <li><a href="#">Lo Nuevo</a></li>
          <li><a href="#">Hombre</a></li>
          <li><a href="#">Mujer</a></li>
          <li><a href="#">Niño/a</a></li>
          <li><a href="#">Ofertas</a></li>
          <li><a href="#">SNKRS</a></li>
        </ul>
        <div class="nav-right">
          <div class="search-box">
            <span class="icon">🔍</span>
            <input type="text" placeholder="Buscar">
          </div>
          <span class="icon">🤍</span>
          <span class="icon">🛒</span>
          <a href="login.php" class="login-btn">Iniciar Sesión</a>
        </div>
      </div>
    </nav>
</body>
</html>

<style>
.logo-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    vertical-align: middle;
    margin-right: 10px;
}
.logo-item {
    list-style: none;
    display: flex;
    align-items: center;
    margin-right: 10px;
    padding: 0;
}
</style>

