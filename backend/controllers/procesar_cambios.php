<?php
include_once('../../database/conexion_bd.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $numero_control = $_POST['numero_control'] ?? null;
    $nombre_completo = $_POST['nombre_completo'] ?? null; // Asegurar nombre_completo coincide con el formulario
    $carrera = $_POST['carrera'] ?? null;
    $semestre = $_POST['semestre'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;

    // Validar que todos los campos requeridos están presentes
    if (!$numero_control || !$nombre_completo || !$carrera || !$semestre || !$fecha_nacimiento) {
        $_SESSION['mensaje_error'] = 'Por favor, completa todos los campos obligatorios.';
        header('Location: ../../frontend/pages/bajas_cambios.php');
        exit;
    }

    try {
        // Conexión a la base de datos
        $conexion = ConexionBDTutorias::getInstancia()->getConexion();

        // Verificar si el alumno existe
        $queryVerificar = "SELECT COUNT(*) FROM alumnos WHERE numero_control = :numero_control";
        $stmtVerificar = $conexion->prepare($queryVerificar);
        $stmtVerificar->bindParam(':numero_control', $numero_control);
        $stmtVerificar->execute();

        if ($stmtVerificar->fetchColumn() == 0) {
            $_SESSION['mensaje_error'] = 'El alumno no existe en el sistema.';
            header('Location: ../../frontend/pages/bajas_cambios.php');
            exit;
        }

        // Actualizar los datos del alumno
        $queryActualizar = "UPDATE alumnos 
                            SET nombre_completo = :nombre_completo, 
                                carrera = :carrera, 
                                semestre = :semestre, 
                                fecha_nacimiento = :fecha_nacimiento
                            WHERE numero_control = :numero_control";

        $stmtActualizar = $conexion->prepare($queryActualizar);
        $stmtActualizar->bindParam(':nombre_completo', $nombre_completo);
        $stmtActualizar->bindParam(':carrera', $carrera);
        $stmtActualizar->bindParam(':semestre', $semestre, PDO::PARAM_INT);
        $stmtActualizar->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmtActualizar->bindParam(':numero_control', $numero_control);

        if ($stmtActualizar->execute()) {
            $_SESSION['mensaje_exito'] = 'Los datos del alumno se actualizaron correctamente.';
        } else {
            $_SESSION['mensaje_error'] = 'Ocurrió un problema al actualizar los datos del alumno.';
        }

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = 'Error en la base de datos: ' . $e->getMessage();
    }

    // Redirigir a la lista de alumnos
    header('Location: ../../frontend/pages/lista_alumnos.php');
    exit;
} else {
    // Redirigir al formulario si el método no es POST
    $_SESSION['mensaje_error'] = 'Acción no permitida.';
    header('Location: ../../frontend/pages/bajas_cambios.php');
    exit;
}
?>
