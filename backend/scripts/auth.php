<?php
// Inicia la sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lista de páginas públicas
$paginas_publicas = ['login.php', 'index.php', 'registro.php'];

// Obtén el nombre del archivo actual
$archivo_actual = basename($_SERVER['PHP_SELF']);

// Verifica si la sesión no está activa y no es una página pública
if (!in_array($archivo_actual, $paginas_publicas) && empty($_SESSION['usuario_id'])) {
    // Redirige al login con ruta absoluta para evitar errores
    header("Location: ../../frontend/pages/login.php");
    exit();
}
?>
