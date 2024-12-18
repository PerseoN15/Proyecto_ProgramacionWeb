<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

session_start();

include_once('../../database/conexion_bd.php');

// Manejar solicitudes OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verificar si el método es POST con _method=DELETE
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    $method = 'DELETE';
}

if ($method === 'DELETE') {
    try {
        // Obtener el número de control
        $numeroControl = $_POST['id'] ?? null;

        if (!$numeroControl) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            exit;
        }

        $conexion = ConexionBDTutorias::getInstancia()->getConexion();

        // Iniciar la transacción
        $conexion->beginTransaction();

        // Verificar si el alumno existe
        $queryVerificar = "SELECT * FROM alumnos WHERE numero_control = :numero_control";
        $stmtVerificar = $conexion->prepare($queryVerificar);
        $stmtVerificar->bindParam(':numero_control', $numeroControl, PDO::PARAM_STR);
        $stmtVerificar->execute();
        $alumno = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if (!$alumno) {
            // Si no se encuentra el alumno, se revierte la transacción
            $conexion->rollBack();
            echo json_encode(['success' => false, 'message' => 'Alumno no encontrado']);
            exit;
        }

        // Guardar la acción reciente en sesión
        $_SESSION['accion_reciente'] = [
            'tipo' => 'baja',
            'datos' => $alumno
        ];

        // Eliminar el alumno
        $queryEliminar = "DELETE FROM alumnos WHERE numero_control = :numero_control";
        $stmtEliminar = $conexion->prepare($queryEliminar);
        $stmtEliminar->bindParam(':numero_control', $numeroControl, PDO::PARAM_STR);
        $stmtEliminar->execute();

        // Registrar la eliminación en la tabla de auditoría
        $queryAuditoria = "INSERT INTO auditoria (numero_control, accion, fecha) VALUES (:numero_control, 'eliminacion', NOW())";
        $stmtAuditoria = $conexion->prepare($queryAuditoria);
        $stmtAuditoria->bindParam(':numero_control', $numeroControl, PDO::PARAM_STR);
        $stmtAuditoria->execute();

        // Confirmar la transacción
        $conexion->commit();

        echo json_encode(['success' => true, 'message' => 'Alumno eliminado correctamente']);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
