<?php
session_start();

// Si el usuario ya ha iniciado sesión, redirígelo a la página principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: inicio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        /* Estilos del login */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #800020, #f4f4f9);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        h1 {
            color: #800020;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .btn-primary {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #800020;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #a0001e;
            transform: scale(1.05);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 1.5em;
            font-weight: bold;
            color: #800020;
            cursor: pointer;
            transition: color 0.3s, transform 0.2s;
        }

        .close-btn:hover {
            color: #a0001e;
            transform: scale(1.2);
        }

        .g-recaptcha {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>
    <!-- Librería de Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const closeButton = document.querySelector(".close-btn");

            closeButton.addEventListener("mouseenter", () => {
                closeButton.style.transform = "scale(1.2)";
                closeButton.style.color = "#a0001e";
            });

            closeButton.addEventListener("mouseleave", () => {
                closeButton.style.transform = "scale(1)";
                closeButton.style.color = "#800020";
            });

            closeButton.addEventListener("click", () => {
                window.location.href = '../../index.html';
            });
        });
    </script>
</head>
<body>
    <div class="login-container">
        <button class="close-btn">&times;</button>
        <h1>Inicio de Sesión</h1>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['mensaje']; 
                unset($_SESSION['mensaje']); // Limpiar el mensaje de la sesión
                ?>
            </div>
        <?php endif; ?>
        <form id="login-form" action="../../backend/controllers/validar_usuario.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <!-- Integración de Google reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LcHA5sqAAAAANaERkihAfLnTsntCZMOFT-KclQR"></div>
            <button type="submit" class="btn-primary">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
