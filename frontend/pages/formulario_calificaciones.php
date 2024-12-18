<?php
include '../../backend/scripts/auth.php';
// formulario_calificaciones.php modificado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Calificaciones</title>
    <link rel="stylesheet" href="style_form_cali.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Calificaciones</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="numero_control">Número de Control</label>
                <input type="text" id="numero_control" name="numero_control" class="form-control" placeholder="Ejemplo: 12345" required>
            </div>
            <div class="form-group">
                <label for="materia">Materia</label>
                <select id="materia" name="materia" class="form-select">
                    <option value="Matemáticas">Matemáticas</option>
                    <option value="Física">Física</option>
                    <option value="Programación">Programación</option>
                </select>
            </div>
            <div class="form-group">
                <label for="calificacion">Calificación</label>
                <input type="number" id="calificacion" name="calificacion" class="form-control" min="0" max="100" placeholder="Ejemplo: 85" required>
            </div>
            <button type="submit" class="btn-primary">Registrar</button>
        </form>
        <p>Simulación activa: Este formulario no guardará datos.</p>
    </div>
</body>
</html>
