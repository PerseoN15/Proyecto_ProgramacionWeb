<?php
include_once('../../database/conexion_bd.php');
include '../../backend/scripts/auth.php';


try {
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();

    // Consulta para obtener alumnos eliminados
    $query = "SELECT id_alumno, numero_control, nombre_completo, carrera, semestre, fecha_nacimiento, fecha_eliminacion
              FROM respaldo_alumnos
              ORDER BY fecha_eliminacion DESC";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $alumnosEliminados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los alumnos eliminados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos Eliminados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #800020, #f4f4f9);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #800020;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead tr {
            background-color: #800020;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ffe5e5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #800020;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #a0001e;
        }
        .no-data {
            text-align: center;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Alumnos Eliminados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Alumno</th>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Semestre</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Fecha de Eliminación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alumnosEliminados)): ?>
                    <?php foreach ($alumnosEliminados as $alumno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno['id_alumno']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['numero_control']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['semestre']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['fecha_eliminacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">No se encontraron alumnos eliminados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="text-align: center;">
            <a href="lista_alumnos.php" class="btn">Volver </a>
        </div>
    </div>
</body>
</html>
