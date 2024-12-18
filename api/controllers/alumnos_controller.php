<?php
require_once '../../database/conexion_bd.php'; // Ruta corregida para la conexión

$conn = ConexionBDTutorias::getInstancia()->getConexion();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

switch ($requestMethod) {
   case 'GET':
    try {
        error_log("Método GET solicitado");

        $filtros = [];
        $sql = "SELECT * FROM alumnos WHERE 1=1";

        // Filtro por número de control
        if (isset($_GET['numero_control'])) {
            $numero_control = $_GET['numero_control'];
            $sql .= " AND numero_control = ?";
            $filtros[] = $numero_control;
        }

        // Filtro por carrera
        if (isset($_GET['carrera']) && $_GET['carrera'] !== "null") {
            $carrera = $_GET['carrera'];
            $sql .= " AND carrera = ?";
            $filtros[] = $carrera;
        }

        // Filtro por semestre
        if (isset($_GET['semestre']) && $_GET['semestre'] !== "null") {
            $semestre = intval($_GET['semestre']);
            $sql .= " AND semestre = ?";
            $filtros[] = $semestre;
        }

        error_log("GET -> Ejecutando consulta: $sql con filtros: " . json_encode($filtros));

        $query = $conn->prepare($sql);
        $query->execute($filtros);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result ?: ["message" => "No se encontraron resultados"]);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log("Error en GET: " . $e->getMessage());
        echo json_encode(["error" => "Error al obtener los datos: " . $e->getMessage()]);
    }
    break;


    case 'POST':
        try {
            $input = file_get_contents("php://input");
            error_log("Método POST solicitado. JSON recibido: $input");

            $data = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                error_log("POST -> JSON inválido recibido");
                echo json_encode(["success" => false, "error" => "JSON inválido"]);
                exit;
            }

            $requiredFields = ['numero_control', 'nombre_completo', 'carrera', 'semestre', 'fecha_nacimiento'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    http_response_code(400);
                    error_log("POST -> Campo requerido faltante: $field");
                    echo json_encode(["success" => false, "error" => "Campo requerido faltante: $field"]);
                    exit;
                }
            }

            $query = $conn->prepare("INSERT INTO alumnos (numero_control, nombre_completo, carrera, semestre, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
            $result = $query->execute([
                $data['numero_control'], $data['nombre_completo'], $data['carrera'], $data['semestre'], $data['fecha_nacimiento']
            ]);

            if ($result) {
                error_log("POST -> Alumno registrado con éxito: " . json_encode($data));
                echo json_encode(["success" => true, "message" => "Alumno registrado con éxito"]);
            } else {
                $errorInfo = $query->errorInfo();
                error_log("POST -> Error al insertar: " . $errorInfo[2]);
                echo json_encode(["success" => false, "error" => "Error al insertar: " . $errorInfo[2]]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            error_log("Error en POST: " . $e->getMessage());
            echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
        }
        break;

    case 'PUT':
        try {
            if (isset($_GET['numero_control'])) {
                $numero_control = $_GET['numero_control'];
            } else {
                http_response_code(400);
                error_log("PUT -> Falta el parámetro numero_control");
                echo json_encode(["success" => false, "error" => "Se requiere el parámetro numero_control"]);
                exit;
            }

            $input = file_get_contents("php://input");
            error_log("Método PUT solicitado. numero_control: $numero_control, JSON recibido: $input");

            $data = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                error_log("PUT -> JSON inválido recibido");
                echo json_encode(["success" => false, "error" => "JSON inválido"]);
                exit;
            }

            $requiredFields = ['nombre_completo', 'carrera', 'semestre', 'fecha_nacimiento'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    http_response_code(400);
                    error_log("PUT -> Campo requerido faltante: $field");
                    echo json_encode(["success" => false, "error" => "Campo requerido faltante: $field"]);
                    exit;
                }
            }

            $query = $conn->prepare("UPDATE alumnos SET nombre_completo = ?, carrera = ?, semestre = ?, fecha_nacimiento = ? WHERE numero_control = ?");
            $result = $query->execute([
                $data['nombre_completo'], $data['carrera'], $data['semestre'], $data['fecha_nacimiento'], $numero_control
            ]);

            if ($result) {
                error_log("PUT -> Alumno actualizado con éxito: " . json_encode($data));
                echo json_encode(["success" => true, "message" => "Alumno actualizado con éxito"]);
            } else {
                $errorInfo = $query->errorInfo();
                error_log("PUT -> Error al actualizar: " . $errorInfo[2]);
                echo json_encode(["success" => false, "error" => "Error al actualizar: " . $errorInfo[2]]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            error_log("Error en PUT: " . $e->getMessage());
            echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
        }
        break;

    case 'DELETE':
        try {
            $id = intval($pathInfo[1]);
            error_log("Método DELETE solicitado. ID: $id");

            $query = $conn->prepare("DELETE FROM alumnos WHERE id_alumno = ?");
            $result = $query->execute([$id]);

            if ($result) {
                error_log("DELETE -> Alumno eliminado con éxito. ID: $id");
                echo json_encode(["success" => true, "message" => "Alumno eliminado con éxito"]);
            } else {
                $errorInfo = $query->errorInfo();
                error_log("DELETE -> Error al eliminar: " . $errorInfo[2]);
                echo json_encode(["success" => false, "error" => "Error al eliminar: " . $errorInfo[2]]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            error_log("Error en DELETE: " . $e->getMessage());
            echo json_encode(["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        error_log("Método no permitido: $requestMethod");
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
