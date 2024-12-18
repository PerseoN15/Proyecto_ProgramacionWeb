<?php


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Incluir la conexión a la base de datos
include_once('../../database/conexion_bd_usuarios.php');
$con = ConexionBDUsuarios::getInstancia();
$conexion = $con->getConexion();

try {
    // Consultar la lista de tutores
    $sql = "SELECT id_tutor, nombre_completo, carrera, materias FROM tutores";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tutores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center text-primary">Lista de Tutores</h1>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Carrera</th>
                    <th>Materias</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tutores)): ?>
                    <?php foreach ($tutores as $tutor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tutor['id_tutor']); ?></td>
                            <td><?php echo htmlspecialchars($tutor['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($tutor['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($tutor['materias']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay tutores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center">
            <a href="menu_principal.php" class="btn btn-secondary mt-3">Regresar al Menú</a>
        </div>
    </div>
</body>
</html>
