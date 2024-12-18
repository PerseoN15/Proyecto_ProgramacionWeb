<?php
include_once("../../backend/controllers/controller_alumno.php");
include '../../backend/scripts/auth.php';


$alumnoDAO = new AlumnoDAO();
$filtros = [
    'numControl' => $_POST['numControl'] ?? '',
    'nombre' => $_POST['nombre'] ?? '',
    'primerAp' => $_POST['primerAp'] ?? '',
    'segundoAp' => $_POST['segundoAp'] ?? '',
    'fechaNacimiento' => $_POST['fechaNacimiento'] ?? '',
    'semestre' => $_POST['semestre'] ?? '',
    'carrera' => $_POST['carrera'] ?? '',
    'tutor' => $_POST['tutor'] ?? '',
    'enRiesgo' => $_POST['enRiesgo'] ?? ''
];

// Llamamos al método para obtener los alumnos filtrados
$resultado = $alumnoDAO->mostrarAlumnosFiltros($filtros);

if ($resultado && $resultado->rowCount() > 0): 
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($fila['Num_Control'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Nombre'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Primer_Apellido'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Segundo_Apellido'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Fecha_Nacimiento'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Semestre'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Carrera'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['Tutor'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($fila['EnRiesgo'] ?? ''); ?></td>
        </tr>
    <?php endwhile;
else: ?>
    <tr>
        <td colspan="9" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
