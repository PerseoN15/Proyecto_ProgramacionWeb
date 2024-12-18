<?php
require_once '../../database/conexion_bd.php';

$conn = ConexionBDTutorias::getInstancia()->getConexion();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        error_log("GET solicitado en consultas_controller");

        // Recuperar parámetros de los filtros
        $numero_control = isset($_GET['numero_control']) ? $_GET['numero_control'] : null;
        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : null;
        $carrera = isset($_GET['carrera']) ? $_GET['carrera'] : null;
        $semestre = isset($_GET['semestre']) ? intval($_GET['semestre']) : null;

        // Depurar valores de entrada
        error_log("Filtros recibidos - Numero_Control: $numero_control, Nombre: $nombre, Carrera: $carrera, Semestre: $semestre");

        // Construir consulta SQL base
        $sql = "SELECT * FROM alumnos WHERE 1=1";
        $params = [];

        // Filtro por número de control (búsqueda parcial)
        if (!empty($numero_control)) {
            $sql .= " AND numero_control LIKE ?";
            $params[] = "%" . $numero_control . "%";
        }

        // Filtro por nombre (búsqueda parcial)
        if (!empty($nombre)) {
            $sql .= " AND nombre_completo LIKE ?";
            $params[] = "%" . $nombre . "%";
        }

        // Filtro por carrera
        if (!empty($carrera)) {
            $sql .= " AND carrera = ?";
            $params[] = $carrera;
        }

        // Filtro por semestre
        if (!empty($semestre)) {
            $sql .= " AND semestre = ?";
            $params[] = $semestre;
        }

        // Ejecutar consulta
        $query = $conn->prepare($sql);
        $query->execute($params);

        // Obtener resultados
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Devolver resultados como JSON
        echo json_encode($result);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error en consultas_controller: " . $e->getMessage());
    echo json_encode(["error" => "Error al obtener los datos"]);
}
?>
