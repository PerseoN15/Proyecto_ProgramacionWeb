<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../../database/conexion_bd.php');

// Recibir datos del formulario
$numControl = $_POST['numero_control'] ?? null;
$nombreCompleto = $_POST['nombre_completo'] ?? null;
$carrera = $_POST['carrera'] ?? null;
$semestre = $_POST['semestre'] ?? null;
$fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;

// Validaciones en el servidor
$errores = [];
if (!$numControl || !ctype_digit($numControl) || strlen($numControl) !== 8) {
    $errores['numero_control'] = "El número de control debe ser numérico y contener exactamente 8 dígitos.";
}
if (!$nombreCompleto || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombreCompleto)) {
    $errores['nombre_completo'] = "El nombre completo debe contener solo letras y espacios.";
}
if (!$carrera) {
    $errores['carrera'] = "La carrera es obligatoria.";
}
if (!$semestre || !is_numeric($semestre) || (int)$semestre < 1 || (int)$semestre > 12) {
    $errores['semestre'] = "El semestre es obligatorio y debe estar entre 1 y 12.";
}
if (!$fechaNacimiento) {
    $errores['fecha_nacimiento'] = "La fecha de nacimiento es obligatoria.";
}

// Si hay errores, redirigir al formulario con mensajes y datos prellenados
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $_SESSION['old_data'] = $_POST; // Guardar datos para prellenar el formulario
    header('Location: ../../frontend/pages/formulario_altas.php');
    exit();
}

try {
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();

    // Validar si el número de control ya existe
    $queryVerificar = "SELECT COUNT(*) AS total FROM alumnos WHERE numero_control = :numero_control";
    $stmtVerificar = $conexion->prepare($queryVerificar);
    $stmtVerificar->bindParam(':numero_control', $numControl, PDO::PARAM_STR);
    $stmtVerificar->execute();

    $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
    if ($resultado['total'] > 0) {
        $_SESSION['mensaje'] = "El número de control ya está registrado.";
        $_SESSION['tipo_mensaje'] = "error";
        $_SESSION['old_data'] = $_POST; // Guardar datos para prellenar el formulario
        header('Location: ../../frontend/pages/formulario_altas.php');
        exit();
    }

    // Iniciar la transacción para la inserción
    $conexion->beginTransaction();

    // Insertar datos del alumno
    $queryInsertar = "INSERT INTO alumnos (numero_control, nombre_completo, carrera, semestre, fecha_nacimiento) 
                      VALUES (:numero_control, :nombre_completo, :carrera, :semestre, :fecha_nacimiento)";
    $stmtInsertar = $conexion->prepare($queryInsertar);
    $stmtInsertar->bindParam(':numero_control', $numControl, PDO::PARAM_STR);
    $stmtInsertar->bindParam(':nombre_completo', $nombreCompleto, PDO::PARAM_STR);
    $stmtInsertar->bindParam(':carrera', $carrera, PDO::PARAM_STR);
    $stmtInsertar->bindParam(':semestre', $semestre, PDO::PARAM_INT);
    $stmtInsertar->bindParam(':fecha_nacimiento', $fechaNacimiento, PDO::PARAM_STR);

    if ($stmtInsertar->execute()) {
        $conexion->commit(); // Confirmar la transacción
        $_SESSION['mensaje'] = "Alumno registrado correctamente.";
        $_SESSION['tipo_mensaje'] = "exito";
        unset($_SESSION['old_data']); // Eliminar datos prellenados tras éxito
    } else {
        $conexion->rollBack(); // Cancelar la transacción en caso de error
        $_SESSION['mensaje'] = "Error al registrar al alumno. Inténtalo de nuevo.";
        $_SESSION['tipo_mensaje'] = "error";
    }
} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack(); // Cancelar la transacción en caso de excepción
    }
    error_log("Error en la base de datos [ID:" . uniqid() . "]: " . $e->getMessage(), 3, "../../logs/errores.log");
    $_SESSION['mensaje'] = "Error inesperado. Contacta al administrador.";
    $_SESSION['tipo_mensaje'] = "error";
    $_SESSION['old_data'] = $_POST; // Guardar datos para prellenar el formulario
}

// Redirigir al formulario con mensaje
header('Location: ../../frontend/pages/formulario_altas.php');
exit();
