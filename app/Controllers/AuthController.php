<?php

namespace App\Controllers;

use App\Core\Database;

class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function showLogin() {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos.';
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }

        try {
            // Consultar la base de datos usando la tabla 'usuario'
            $sql = "SELECT id, nombre, correo, contraseña, rol FROM usuario WHERE correo = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Para propósitos de debugging
            error_log("Intento de login - Email: " . $email);
            error_log("Usuario encontrado: " . ($user ? "Sí" : "No"));
            if ($user) {
                error_log("Contraseña ingresada: " . $password);
                error_log("Contraseña en DB: " . $user['contraseña']);
            }

            if ($user && $user['contraseña'] === $password) { // Comparación directa por ahora
                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['correo'];
                $_SESSION['role'] = $user['rol'];
                $_SESSION['nombre'] = $user['nombre'];

                // Redirigir según el rol
                if ($user['rol'] === 'admin') {
                    header('Location: /Tienda_SNKRS/public/admin');
                } else {
                    header('Location: /Tienda_SNKRS/public/productos/nuevo');
                }
                exit();
            } else {
                $_SESSION['error'] = 'Credenciales inválidas.';
                header('Location: /Tienda_SNKRS/public/login');
                exit();
            }
        } catch (\Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            $_SESSION['error'] = 'Error al intentar iniciar sesión. Por favor, inténtalo de nuevo.';
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }
    }

    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();

        // Destruir la sesión
        session_destroy();

        // Redirigir al login
        header('Location: /Tienda_SNKRS/public/login');
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($username) || empty($password)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos.';
            header('Location: /Tienda_SNKRS/public/login');
            exit();
        }

        try {
            // Verificar si el email ya existe
            $sql = "SELECT id FROM usuario WHERE correo = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Este correo electrónico ya está registrado.';
                header('Location: /Tienda_SNKRS/public/login');
                exit();
            }

            // Crear el usuario
            $sql = "INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, 'usuario')";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt->execute([$username, $email, $password])) {
                $_SESSION['success'] = 'Registro exitoso. Por favor, inicie sesión.';
                header('Location: /Tienda_SNKRS/public/login');
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario.';
                header('Location: /Tienda_SNKRS/public/login');
            }
        } catch (\Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            $_SESSION['error'] = 'Error al registrar el usuario. Por favor, inténtalo de nuevo.';
            header('Location: /Tienda_SNKRS/public/login');
        }
        exit();
    }
} 