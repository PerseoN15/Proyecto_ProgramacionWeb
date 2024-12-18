<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include_once('../../database/conexion_bd.php');

$response = [
    'existe' => false,
    'mensaje' => ''
];

try {
    // Validar que se envíe el número de control
    if (empty($_POST['numero_control']) || !ctype_digit($_POST['numero_control']) || strlen($_POST['numero_control']) !== 8) {
        $response['mensaje'] = "Número de control inválido.";
        echo json_encode($response);
        exit();
    }

    $numeroControl = $_POST['numero_control'];

    // Conectar a la base de datos
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();

    // Consultar si el número de control ya existe
    $query = "SELECT COUNT(*) AS total FROM alumnos WHERE numero_control = :numero_control";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':numero_control', $numeroControl, PDO::PARAM_STR);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado['total'] > 0) {
        $response['existe'] = true;
        $response['mensaje'] = "El número de control ya está registrado.";
    } else {
        $response['mensaje'] = "El número de control está disponible.";
    }
} catch (PDOException $e) {
    error_log("Error en la verificación del número de control: " . $e->getMessage(), 3, "../../logs/errores.log");
    $response['mensaje'] = "Error al verificar el número de control. Por favor, intenta nuevamente.";
}

// Devolver la respuesta como JSON
echo json_encode($response);
exit();
