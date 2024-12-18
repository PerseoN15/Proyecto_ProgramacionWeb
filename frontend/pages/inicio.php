<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión de Alumnos</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style_inicio.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #800020, #f4f4f9);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #800020;
            margin-bottom: 20px;
        }

        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body i {
            font-size: 3em;
            color: #800020;
            transition: color 0.3s ease;
        }

        .card-body i:hover {
            color: #a0001e;
        }

        .container {
            padding-top: 50px;
        }
    </style>
</head>
<body>
    <?php include('menu_principal.php'); ?>

    <div class="container mt-5 text-center">
        <h1 class="text-primary">Bienvenido al Sistema de Gestión de Alumnos</h1>
        <p class="lead text-secondary mt-3">Aquí podrás gestionar de forma sencilla las altas, modificaciones, eliminaciones y consultas de alumnos registrados en el sistema.</p>

        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <a href="/formulario-altas">
                            <i class="bi bi-person-plus-fill"></i>
                        </a>
                        <h5 class="card-title mt-3">Agregar Alumnos</h5>
                        <p class="card-text">Registra nuevos alumnos en el sistema con información completa y precisa.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <a href="/bajas-cambios">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <h5 class="card-title mt-3">Modificar/Eliminar</h5>
                        <p class="card-text">Realiza modificaciones o elimina registros según sea necesario.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <a href="/lista-alumnos">
                            <i class="bi bi-search"></i>
                        </a>
                        <h5 class="card-title mt-3">Consultar Alumnos</h5>
                        <p class="card-text">Busca y consulta información detallada de los alumnos registrados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
