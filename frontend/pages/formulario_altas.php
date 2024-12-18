<?php
// Incluir la autenticación para proteger la página
include '../../backend/scripts/auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="../../backend/scripts/validacion_de_altas.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Altas</title>
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
            margin: auto;
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

        #log-error {
            display: none;
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            background: rgba(255, 200, 200, 0.7);
            border: 1px solid red;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-submit {
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
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-submit:hover {
            background-color: #a0001e;
            transform: scale(1.05);
        }

        .btn-back {
            display: inline-block;
            margin-top: 15px;
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
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-back:hover {
            background-color: #993d00;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Nuevo Alumno</h2>
        <div id="log-error" style="display: none; color: red; font-weight: bold; margin-bottom: 10px;"></div>
        <div id="log-error"></div>
        <form action="../../backend/controllers/procesar_altas.php" method="POST">
            <div class="form-group">
                <label for="numero_control">Número de Control</label>
                <input type="text" id="numero_control" name="numero_control" placeholder="Ejemplo: 12345678" required>
            </div>
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ejemplo: Juan Pérez" required>
            </div>
            <div class="form-group">
                <label for="carrera">Carrera</label>
                <select id="carrera" name="carrera" required>
                    <option value="">Selecciona una carrera</option>
                    <option value="ISC">Ingeniería en Sistemas Computacionales</option>
                    <option value="IM">Ingeniería en Mecatrónica</option>
                    <option value="LA">Licenciatura en Administración</option>
                    <option value="IIA">Ingeniería en Industrias Alimentarias</option>
                    <option value="CP">Licenciatura en Contador Público</option>
                </select>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre</label>
                <select id="semestre" name="semestre" required>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <button type="submit" class="btn-submit">Registrar</button>
        </form>
        <a href="menu_principal.php" class="btn-back">Volver al Menú Principal</a>
    </div>
</body>
</html>
