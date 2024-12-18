<?php
include '../../backend/scripts/auth.php'; // Verifica la sesión antes de cargar el contenido

// Recibir datos enviados desde lista_alumnos.php
$numero_control = $_GET['numero_control'] ?? '';
$nombre_completo = $_GET['nombre_completo'] ?? '';
$carrera = $_GET['carrera'] ?? '';
$semestre = $_GET['semestre'] ?? '';
$fecha_nacimiento = $_GET['fecha_nacimiento'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, rgba(128, 0, 32, 0.8), rgba(244, 244, 249, 0.8));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            max-width: 600px;
            width: 100%;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], select, input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, select:focus, input[type="date"]:focus {
            border-color: #800020;
            outline: none;
            box-shadow: 0 0 5px rgba(128, 0, 32, 0.5);
        }

        .btn-primary {
            display: inline-block;
            width: 100%;
            padding: 15px;
            background-color: #800020;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-primary:hover {
            background-color: #a0001e;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .btn-back, .btn-secondary {
            display: inline-block;
            padding: 15px;
            background-color: #cc5500;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-back:hover, .btn-secondary:hover {
            background-color: #993d00;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        p {
            text-align: center;
            color: #555;
            margin-top: 15px;
        }
    </style>
    <script src="../../backend/scripts/validaciones_cambios.js" defer></script>
</head>
<body>
    <div class="form-container">
        <h2>Modificaciones de Alumnos</h2>
        <form action="../../backend/controllers/procesar_cambios.php" method="POST">
            <div class="form-group">
                <label for="numero_control">Número de Control</label>
                <input type="text" id="numero_control" name="numero_control" value="<?php echo htmlspecialchars($numero_control); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($nombre_completo); ?>" required>
            </div>
            <div class="form-group">
                <label for="carrera">Carrera</label>
                <select id="carrera" name="carrera">
                    <option value="ISC" <?php echo $carrera === 'ISC' ? 'selected' : ''; ?>>Ingeniería en Sistemas Computacionales</option>
                    <option value="IM" <?php echo $carrera === 'IM' ? 'selected' : ''; ?>>Ingeniería en Mecatrónica</option>
                    <option value="LA" <?php echo $carrera === 'LA' ? 'selected' : ''; ?>>Licenciatura en Administración</option>
                    <option value="IIA" <?php echo $carrera === 'IIA' ? 'selected' : ''; ?>>Ingeniería en Industrias Alimentarias</option>
                    <option value="CP" <?php echo $carrera === 'CP' ? 'selected' : ''; ?>>Licenciatura en Contador Público</option>
                </select>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre</label>
                <select id="semestre" name="semestre">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo (int)$semestre === $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required>
            </div>
            <button type="submit" class="btn-primary">Cambiar</button>
        </form>
        <div class="btn-container">
            <a href="lista_alumnos.php" class="btn-secondary">Regresar</a>
            <a href="menu_principal.php" class="btn-back">Volver al Menú Principal</a>
        </div>
    </div>
</body>
</html>
