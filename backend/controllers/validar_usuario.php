<?php
session_start();

// Obtener los datos del formulario
$usuario = $_POST['usuario'] ?? null;
$password = $_POST['password'] ?? null;
$captcha = $_POST['g-recaptcha-response'] ?? null;

// Validar que los campos no estén vacíos
if (!$usuario || !$password) {
    $_SESSION['mensaje'] = 'Error: Usuario y contraseña son obligatorios.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Validar el CAPTCHA
if (!$captcha) {
    $_SESSION['mensaje'] = 'Por favor, completa el CAPTCHA.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Verificar el CAPTCHA con Google
$secretKey = '6LcHA5sqAAAAAL6HY9jambHjj4Si_Fr5sKckN0h_';
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
$responseKeys = json_decode($response, true);

if (!$responseKeys['success']) {
    $_SESSION['mensaje'] = 'Error al validar el CAPTCHA. Inténtalo de nuevo.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Incluir la conexión a la base de datos
include_once('../../database/conexion_bd_usuarios.php');
$con = ConexionBDUsuarios::getInstancia();
$conexion = $con->getConexion();

try {
    // Buscar al usuario en la base de datos
    $sql = "SELECT id_usuario, usuario, password, rol FROM usuarios WHERE usuario = :usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    // Verificar si el usuario existe
    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validar la contraseña
        if (password_verify($password, $row['password'])) {
            // Configurar la sesión correctamente
            $_SESSION['usuario_id'] = $row['id_usuario'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rol'] = $row['rol'];
            header('Location: ../../frontend/pages/menu_principal.php');
            exit();
        } else {
            $_SESSION['mensaje'] = 'Contraseña incorrecta. Por favor, inténtalo de nuevo.';
            header('Location: ../../frontend/pages/login.php');
            exit();
        }
    } else {
        $_SESSION['mensaje'] = 'Usuario no encontrado. Por favor, regístrate primero.';
        header('Location: ../../frontend/pages/login.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['mensaje'] = 'Error en la consulta a la base de datos: ' . $e->getMessage();
    header('Location: ../../frontend/pages/login.php');
    exit();
}
?>
