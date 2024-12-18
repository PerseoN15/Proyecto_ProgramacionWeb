<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos del formulario */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #800020, #f4f4f9);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .registro-container {
            position: relative;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            border-radius: 8px;
            font-size: 1em;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #800020;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-primary:hover {
            background-color: #a0001e;
            transform: scale(1.05);
        }
        .form-footer a {
            color: #800020;
            text-decoration: none;
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
    </style>
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
    <div class="registro-container">
        <button class="close-btn">&times;</button>
        <h1>Registro de Usuario</h1>
        <form action="../../backend/controllers/procesar_registro.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="btn-primary">Registrarse</button>
        </form>
        <div class="form-footer">
            <a href="login.php">Volver al Inicio de Sesión</a>
        </div>
    </div>
</body>
</html>
