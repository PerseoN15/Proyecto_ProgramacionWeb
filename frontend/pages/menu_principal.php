<?php
// menu_principal.php
session_start();

// Validar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario
$usuario = htmlspecialchars($_SESSION['usuario']);
$rol = $_SESSION['rol'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #800020, #f4f4f9);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .navbar {
            background-color: #800020;
            padding: 15px;
            display: flex;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }

        .btn {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #a0001e;
            border-radius: 8px;
            border: 2px solid #b0b0b0;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #8b0000;
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-warning {
            background-color: #c76c40;
            border: 2px solid #b0b0b0;
        }

        .btn-warning:hover {
            background-color: #a55632;
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .container {
            text-align: center;
            margin: 50px auto;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            padding: 30px;
            animation: fadeIn 0.7s ease-in-out;
        }

        h1 {
            font-size: 2.8em;
            color: #800020;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 1.2em;
            margin-bottom: 10px;
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

        .welcome-message {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul class="navbar-nav">
            <li><a href="../../frontend/pages/lista_alumnos.php" class="btn">Lista de Alumnos</a></li>
            <li><a href="../../frontend/pages/formulario_altas.php" class="btn">Agregar Alumno</a></li>
            <li><a href="../../backend/scripts/cerrar_sesion.php" class="btn btn-warning">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <main class="container">
        <h1>Bienvenido, <?php echo $usuario; ?>!</h1>
        <div class="welcome-message">
            <p>Rol: <?php echo $rol; ?></p>
            <p>Usa las opciones del menú para navegar por el sistema.</p>
        </div>
    </main>
</body>
</html>
