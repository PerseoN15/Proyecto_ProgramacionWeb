<?php
require_once '../../database/conexion_bd.php';

$conn = ConexionBDTutorias::getInstancia()->getConexion();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        error_log("PUT solicitado en cambios_controller");

        // Leer los datos enviados en el cuerpo de la solicitud
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Verificar que el JSON se haya decodificado correctamente
        if ($data === null) {
            http_response_code(400);
            echo json_encode(["error" => "Datos inválidos o formato JSON incorrecto"]);
            exit();
        }

        // Validar que se reciban todos los campos necesarios
        if (
            isset($data['numero_control']) &&
            isset($data['nombre_completo']) &&
            isset($data['carrera']) &&
            isset($data['semestre']) &&
            isset($data['fecha_nacimiento'])
        ) {
            $numero_control = $data['numero_control'];
            $nombre_completo = $data['nombre_completo'];
            $carrera = $data['carrera'];
            $semestre = intval($data['semestre']);
            $fecha_nacimiento = $data['fecha_nacimiento'];

            // Preparar la consulta para actualizar los datos del alumno
            $sql = "UPDATE alumnos SET nombre_completo = ?, carrera = ?, semestre = ?, fecha_nacimiento = ? WHERE numero_control = ?";
            $query = $conn->prepare($sql);

            $result = $query->execute([$nombre_completo, $carrera, $semestre, $fecha_nacimiento, $numero_control]);

            if ($result) {
                echo json_encode(["success" => true, "message" => "Alumno actualizado con éxito"]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "error" => "No se pudo actualizar el alumno"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos para la actualización"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error en cambios_controller: " . $e->getMessage());
    echo json_encode(["error" => "Error al actualizar el alumno"]);
}
?>
