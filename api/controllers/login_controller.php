<?php
require_once '../../database/conexion_bd_usuarios.php'; // Incluye la clase de conexión

// Encabezados para la respuesta HTTP
header("Content-Type: application/json");
http_response_code(200); // Respuesta exitosa por defecto

// Obtener la conexión usando el Singleton
$conn = ConexionBDUsuarios::getInstancia()->getConexion();

// Obtener los datos enviados por la app
$data = json_decode(file_get_contents("php://input"), true);

// Validar que los datos existan
if (!isset($data['usuario']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Campos incompletos"]);
    exit;
}

try {
    // Preparar la consulta
    $query = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $query->execute([$data['usuario']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($data['password'], $user['password'])) {
            // Usuario autenticado correctamente
            echo json_encode([
                "success" => true, 
                "message" => "Autenticación exitosa", 
                "rol" => $user['rol'] // Corregido de 'role' a 'rol'
            ]);
        } else {
            // Contraseña incorrecta
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
        }
    } else {
        // Usuario no encontrado
        echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
