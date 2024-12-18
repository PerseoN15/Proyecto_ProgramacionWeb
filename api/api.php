<?php
header("Content-Type: application/json");

// Incluir archivos de conexión y utilidades
require_once 'conexion_bd.php';
require_once 'conexion_bd_usuarios.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
$pathInfo = explode("/", trim($_SERVER["PATH_INFO"], "/"));

// Procesar rutas
switch ($pathInfo[0]) {
    case 'alumnos':
        require 'controllers/alumnos_controller.php';
        break;

    case 'usuarios':
        if ($requestMethod === 'POST') {
            require 'controllers/usuarios_controller.php';
        }
        break;

    case 'login':
        if ($requestMethod === 'POST') {
            require 'controllers/login_controller.php';
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}
?>
