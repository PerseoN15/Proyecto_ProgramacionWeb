<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../backend/scripts/auth.php';
include_once('../../database/conexion_bd.php');

// Inicialización de variables
$sesiones = [];

try {
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();

    // Consulta con JOIN para obtener los datos
    $querySesiones = "
        SELECT 
            alumnos.numero_control,
            alumnos.nombre_completo AS nombre,
            alumnos.carrera,
            tutoria.nombre_tutor AS nombre_tutor,
            tutoria.fecha AS fecha_tutoria
        FROM 
            alumnos
        LEFT JOIN 
            tutoria 
        ON 
            alumnos.numero_control = tutoria.numero_control
        ORDER BY 
            alumnos.numero_control ASC
    ";
    $stmtSesiones = $conexion->prepare($querySesiones);
    $stmtSesiones->execute();
    $sesiones = $stmtSesiones->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Sesiones</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #800020, #f4f4f9);
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #800020;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead tr {
            background-color: #800020;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ffe5e5;
        }
        .btn {
            padding: 10px 20px;
            background-color: #800020;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #a0001e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sesiones de Tutorías</h2>
        <table>
            <thead>
                <tr>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Nombre del Tutor</th>
                    <th>Fecha de Tutoría</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sesiones)): ?>
                    <?php foreach ($sesiones as $sesion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sesion['numero_control']); ?></td>
                            <td><?php echo htmlspecialchars($sesion['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($sesion['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($sesion['nombre_tutor'] ?? 'Sin Tutor'); ?></td>
                            <td><?php echo htmlspecialchars($sesion['fecha_tutoria'] ?? 'Sin Fecha'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No se encontraron sesiones de tutoría.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="menu_principal.php" class="btn">Volver al Menú Principal</a>
    </div>
</body>
</html>
