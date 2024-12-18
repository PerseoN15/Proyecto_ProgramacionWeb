<?php
require_once '../../database/conexion_bd.php';

$conn = ConexionBDTutorias::getInstancia()->getConexion();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        error_log("DELETE solicitado en bajas_controller");

        // Obtener el ID desde los parámetros de la URL
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($id) {
            $query = $conn->prepare("DELETE FROM alumnos WHERE id_alumno = ?");
            $result = $query->execute([$id]);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Alumno eliminado"]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar el alumno"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error en bajas_controller: " . $e->getMessage());
    echo json_encode(["error" => "Error al eliminar el alumno"]);
}

?>
