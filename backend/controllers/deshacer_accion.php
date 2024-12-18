<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../../database/conexion_bd.php');

// Verificar si hay una acción reciente en la sesión
if (!isset($_SESSION['accion_reciente'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No hay ninguna acción reciente para deshacer.'
    ]);
    exit();
}

$accionReciente = $_SESSION['accion_reciente'];

try {
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();

    if ($accionReciente['tipo'] === 'baja') {
        $queryRestaurar = "INSERT INTO alumnos (numero_control, nombre_completo, carrera, semestre, fecha_nacimiento) 
                           VALUES (:numero_control, :nombre_completo, :carrera, :semestre, :fecha_nacimiento)";
        $stmt = $conexion->prepare($queryRestaurar);
        $stmt->bindParam(':numero_control', $accionReciente['datos']['numero_control']);
        $stmt->bindParam(':nombre_completo', $accionReciente['datos']['nombre_completo']);
        $stmt->bindParam(':carrera', $accionReciente['datos']['carrera']);
        $stmt->bindParam(':semestre', $accionReciente['datos']['semestre']);
        $stmt->bindParam(':fecha_nacimiento', $accionReciente['datos']['fecha_nacimiento']);
        $stmt->execute();
        $mensaje = 'El alumno eliminado ha sido restaurado con éxito.';
    } elseif ($accionReciente['tipo'] === 'modificacion') {
        $queryRevertir = "UPDATE alumnos SET nombre_completo = :nombre_completo, carrera = :carrera, semestre = :semestre, fecha_nacimiento = :fecha_nacimiento 
                          WHERE numero_control = :numero_control";
        $stmt = $conexion->prepare($queryRevertir);
        $stmt->bindParam(':numero_control', $accionReciente['datos']['numero_control']);
        $stmt->bindParam(':nombre_completo', $accionReciente['datos']['nombre_completo']);
        $stmt->bindParam(':carrera', $accionReciente['datos']['carrera']);
        $stmt->bindParam(':semestre', $accionReciente['datos']['semestre']);
        $stmt->bindParam(':fecha_nacimiento', $accionReciente['datos']['fecha_nacimiento']);
        $stmt->execute();
        $mensaje = 'La modificación realizada ha sido revertida con éxito.';
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de acción desconocido.'
        ]);
        exit();
    }

    unset($_SESSION['accion_reciente']);

    echo json_encode([
        'success' => true,
        'message' => $mensaje
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al deshacer la acción: ' . $e->getMessage()
    ]);
}
?>
