<?php
require_once __DIR__ . '/../../database/conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Validar campos no vacíos
    if (empty($usuario) || empty($password)) {
        echo "<script>alert('Por favor, completa todos los campos.'); window.history.back();</script>";
        exit();
    }

    // Hash de la contraseña
    $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

    try {
        $conexion = ConexionBDTutorias::getInstancia()->getConexion();

        // Insertar nuevo usuario
        $query = "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordHashed, PDO::PARAM_STR);
        $stmt->execute();

        echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = '../../frontend/pages/login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: El usuario ya existe o ocurrió un problema al registrar.'); window.history.back();</script>";
    }
}
?>
