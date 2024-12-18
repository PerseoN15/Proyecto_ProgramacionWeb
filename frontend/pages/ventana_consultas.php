<?php
require_once('menu_principal.php'); // Aquí añades el menú principal al inicio
require_once('../../backend/controllers/controller_alumno.php');
$dao = new AlumnoDAO();
include '../../backend/scripts/auth.php';
$carreras = $dao->obtenerCarreras();
$tutores = $dao->obtenerTutores();
$enRiesgoOptions = ['Sí', 'No'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Alumnos</title>
    <link rel="stylesheet" href="../../frontend/styles/style_consulta.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Listado de Alumnos</h1>

        <!-- Formulario para filtros -->
        <form method="post" action="ventana_consultas.php" id="filterForm">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="text" id="numControl" name="numControl" class="form-control" placeholder="Número de Control" 
                           oninput="applyFilters()">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" 
                           oninput="applyFilters()">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" id="primerAp" name="primerAp" class="form-control" placeholder="Primer Apellido" 
                           oninput="applyFilters()">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" id="segundoAp" name="segundoAp" class="form-control" placeholder="Segundo Apellido" 
                           oninput="applyFilters()">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" id="fechaNacimiento" name="fechaNacimiento" class="form-control" placeholder="Fecha de Nacimiento" 
                           oninput="applyFilters()">
                </div>
                <div class="form-group col-md-3">
                    <input type="number" id="semestre" name="semestre" class="form-control" placeholder="Semestre" 
                           oninput="applyFilters()">
                </div>
                <!-- Campo para seleccionar Carrera -->
<div class="form-group col-md-3">
<select id="carrera" name="carrera" class="form-control" onchange="applyFilters()">
    <option value="">Todas las carreras</option>
    <?php foreach ($carreras as $carrera): ?>
        <option value="<?= $carrera['Nombre_carrera'] ?>"><?= $carrera['Nombre_carrera'] ?></option>
    <?php endforeach; ?>
</select>

</div>

<!-- Campo para seleccionar Tutor -->
<div class="form-group col-md-3">
<select id="tutor" name="tutor" class="form-control" onchange="applyFilters()">
    <option value="">Todos los tutores</option>
    <?php foreach ($tutores as $tutor): ?>
        <option value="<?= $tutor['titulo'] . ' ' . $tutor['nombre'] . ' ' . $tutor['primer_apellido'] . ' ' . $tutor['segundo_apellido'] ?>">
            <?= $tutor['titulo'] . ' ' . $tutor['nombre'] . ' ' . $tutor['primer_apellido'] . ' ' . $tutor['segundo_apellido'] ?>
        </option>
    <?php endforeach; ?>
</select>

</div>

<!-- Campo para seleccionar si está en Riesgo -->
<div class="form-group col-md-3">
    <select id="enRiesgo" name="enRiesgo" class="form-control" onchange="applyFilters()">
        <option value="">Todos</option>
        <?php foreach ($enRiesgoOptions as $option): ?>
            <option value="<?= $option ?>"><?= $option ?></option>
        <?php endforeach; ?>
    </select>
</div>
                <div class="form-group col-md-3">
                    <button type="button" class="btn btn-secondary" onclick="resetFilters()">Restablecer</button>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <table class="table table-striped table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Tutor</th>
                    <th>En Riesgo</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>

    <script>
        function applyFilters() {
            const formData = new FormData(document.getElementById("filterForm"));
            
            fetch("alumnos_tabla.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("tableBody").innerHTML = data;
            })
            .catch(error => console.error("Error:", error));
        }

        function resetFilters() {
            document.getElementById("filterForm").reset();
            applyFilters();
        }

        window.onload = applyFilters;
    </script>
</body>
</html>
