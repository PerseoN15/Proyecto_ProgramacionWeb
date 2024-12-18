<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../../database/conexion_bd.php');

$carrera = $_POST['carrera'] ?? null;
$alumnos = [];
$carreras = [];

try {
    // Obtener la lista de carreras desde la base de datos
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();
    $queryCarreras = "SELECT DISTINCT carrera FROM alumnos";
    $stmtCarreras = $conexion->prepare($queryCarreras);
    $stmtCarreras->execute();
    $carreras = $stmtCarreras->fetchAll(PDO::FETCH_COLUMN);

    // Si se envió una carrera, buscar los alumnos de esa carrera
    if ($carrera) {
        $query = "SELECT numero_control, nombre_completo, carrera, semestre, fecha_nacimiento 
                  FROM alumnos 
                  WHERE carrera = :carrera";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $stmt->execute();
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda por Carrera</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #800020, #f4f4f9);
            color: #333;
        }
        .container {
            margin: 30px auto;
            max-width: 800px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #800020;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #800020;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid #b0b0b0;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            background-color: #a0001e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead tr {
            background-color: #800020;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ffe5e5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Búsqueda por Carrera</h2>
        <form method="POST" action="busqueda_por_carrera.php">
            <div class="form-group">
                <label for="carrera">Seleccione la Carrera:</label>
                <select id="carrera" name="carrera" required>
                    <option value="">Seleccione una carrera</option>
                    <?php foreach ($carreras as $carreraOption): ?>
                        <option value="<?php echo htmlspecialchars($carreraOption); ?>" 
                            <?php echo $carrera === $carreraOption ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($carreraOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Buscar</button>
        </form>

        <?php if (!empty($alumnos)): ?>
            <h3>Resultados para la Carrera: <?php echo htmlspecialchars($carrera); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Número de Control</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Semestre</th>
                        <th>Fecha de Nacimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno['numero_control']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['semestre']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($carrera): ?>
            <p>No se encontraron alumnos en la carrera seleccionada.</p>
        <?php endif; ?>
        <a href="lista_alumnos.php" class="btn">Volver a la Lista</a>
    </div>
</body>
</html>
