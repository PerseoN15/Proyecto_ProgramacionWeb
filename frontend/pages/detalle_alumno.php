<?php
// detalle_alumno.php modificado

// Datos simulados de un alumno
$alumno = [
    'numero_control' => '12345',
    'nombre' => 'Juan Pérez',
    'carrera' => 'Ingeniería en Sistemas Computacionales',
    'semestre' => 5,
    'promedio' => 85.5
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Alumno</title>
    <link rel="stylesheet" href="style_lista_alumnos.css">
</head>
<body>
    <div class="container">
        <h2>Detalle del Alumno</h2>
        <table>
            <tr>
                <th>Número de Control</th>
                <td><?php echo $alumno['numero_control']; ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo $alumno['nombre']; ?></td>
            </tr>
            <tr>
                <th>Carrera</th>
                <td><?php echo $alumno['carrera']; ?></td>
            </tr>
            <tr>
                <th>Semestre</th>
                <td><?php echo $alumno['semestre']; ?></td>
            </tr>
            <tr>
                <th>Promedio</th>
                <td><?php echo $alumno['promedio']; ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
