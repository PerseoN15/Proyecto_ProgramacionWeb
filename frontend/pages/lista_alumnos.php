<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../backend/scripts/auth.php';
include_once('../../database/conexion_bd.php');

// Inicialización de variables
$alumnos = [];
$accionReciente = $_SESSION['accion_reciente'] ?? null; // Guardar la acción reciente

try {
    $conexion = ConexionBDTutorias::getInstancia()->getConexion();
    $query = "SELECT numero_control, nombre_completo, carrera, semestre, fecha_nacimiento FROM alumnos";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los alumnos: " . $e->getMessage());
}

function calcularEdad($fechaNacimiento) {
    if (empty($fechaNacimiento)) return "Desconocida";
    try {
        $fechaActual = new DateTime();
        $fechaNacimiento = new DateTime($fechaNacimiento);
        return $fechaActual->diff($fechaNacimiento)->y;
    } catch (Exception $e) {
        error_log("Error al calcular la edad: " . $e->getMessage());
        return "Error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #800020, #f4f4f9);
            color: #333;
            display: flex;
        }
        .menu-lateral {
            width: 300px;
            background: #800020;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -300px;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            transition: left 0.3s;
        }
        .menu-lateral.open {
            left: 0;
        }
        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #800020;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
            z-index: 1000;
        }
        .menu-toggle:hover {
            background: #a0001e;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .container {
            margin: 30px auto;
            max-width: 800px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-left: 320px;
            transition: margin-left 0.3s;
        }
        h2 {
            text-align: center;
            color: #800020;
            margin-bottom: 20px;
            font-size: 2.5em;
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
            cursor: pointer;
        }
        th {
            font-weight: bold;
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
        .btn-seleccionar, .btn-eliminar {
            font-weight: bold;
        }
        .btn-eliminar {
            background-color: #cc0000;
            border-color: #b00000;
        }
        .btn-eliminar:hover {
            background-color: #ff1a1a;
        }
        .btn[disabled] {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <script>
        
        document.addEventListener("DOMContentLoaded", () => {
            const menu = document.querySelector(".menu-lateral");
            const toggle = document.querySelector(".menu-toggle");
            toggle.addEventListener("click", () => menu.classList.toggle("open"));

            // Filtro dinámico
            const filtrarAlumnos = () => {
                const numeroControl = document.getElementById("numero_control").value.toLowerCase();
                const nombre = document.getElementById("nombre").value.toLowerCase();
                const carrera = document.getElementById("carrera").value.toLowerCase();
                const semestre = document.getElementById("semestre").value.toLowerCase();

                document.querySelectorAll("table tbody tr").forEach(row => {
                    const cells = row.querySelectorAll("td");
                    const matches = (
                        (!numeroControl || cells[0].textContent.toLowerCase().includes(numeroControl)) &&
                        (!nombre || cells[1].textContent.toLowerCase().includes(nombre)) &&
                        (!carrera || cells[2].textContent.toLowerCase().includes(carrera)) &&
                        (!semestre || cells[3].textContent.toLowerCase().includes(semestre))
                    );
                    row.style.display = matches ? "" : "none";
                });
            };

            document.getElementById("numero_control").addEventListener("input", filtrarAlumnos);
            document.getElementById("nombre").addEventListener("input", filtrarAlumnos);
            document.getElementById("carrera").addEventListener("change", filtrarAlumnos);
            document.getElementById("semestre").addEventListener("change", filtrarAlumnos);

            // Botón de cancelar
            const btnCancelar = document.getElementById("btn-cancelar");
            if (btnCancelar) {
                btnCancelar.addEventListener("click", (event) => {
                    event.preventDefault();
                    fetch(`../../backend/controllers/deshacer_accion.php`, {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message || "No se pudo deshacer la acción.");
                        }
                    })
                    .catch(error => {
                        console.error("Error al deshacer:", error);
                        alert("Ocurrió un error inesperado.");
                    });
                });
            }

            // Botón de eliminar
            document.querySelectorAll(".btn-eliminar").forEach(button => {
                button.addEventListener("click", (event) => {
                    const alumnoId = button.dataset.id;
                    if (confirm(`¿Estás seguro de que deseas eliminar al alumno con número de control ${alumnoId}?`)) {
                        fetch(`../../backend/controllers/eliminar_alumno.php`, {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${alumnoId}&_method=DELETE`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message || "Error al eliminar el alumno.");
                            }
                        })
                        .catch(error => {
                            console.error("Error al eliminar:", error);
                            alert("Error inesperado al intentar eliminar el alumno.");
                        });
                    }
                });
            });
        });
    </script>
</head>
<body>
    <button class="menu-toggle">☰</button>
    <div class="menu-lateral">
        <h2>Menú</h2>
        <div class="form-group">
            <label for="numero_control">Número de Control</label>
            <input type="text" id="numero_control" placeholder="Ejemplo: 12345678">
        </div>
        <div class="form-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" placeholder="Ejemplo: Geovani Giorgio">
        </div>
        <div class="form-group">
            <label for="carrera">Carrera</label>
            <select id="carrera">
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
            <select id="semestre">
                <option value="">Selecciona un semestre</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <div class="container">
        <h2>Lista de Alumnos</h2>
        <a href="menu_principal.php" class="btn">Volver</a>
        <a href="ver_sesiones.php" class="btn">Ver sesiones</a>
        <a href="alumnos_eliminados.php" class="btn">Alumnos Eliminados</a>
        <a href="busqueda_por_carrera.php" class="btn">Búsqueda por Carrera</a>
        <a href="#" class="btn" id="btn-cancelar" <?php echo $accionReciente ? '' : 'disabled'; ?>>Cancelar</a>
        <table>
            <thead>
                <tr>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Semestre</th>
                    <th>Edad</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alumnos)): ?>
                    <?php foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno['numero_control']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['semestre']); ?></td>
                            <td><?php echo calcularEdad($alumno['fecha_nacimiento']); ?> años</td>
                            <td>
                                <a href="bajas_cambios.php?numero_control=<?php echo urlencode($alumno['numero_control']); ?>&nombre_completo=<?php echo urlencode($alumno['nombre_completo']); ?>&carrera=<?php echo urlencode($alumno['carrera']); ?>&semestre=<?php echo urlencode($alumno['semestre']); ?>&fecha_nacimiento=<?php echo urlencode($alumno['fecha_nacimiento']); ?>" class="btn btn-seleccionar">Modificar</a>
                                <button data-id='<?php echo htmlspecialchars($alumno['numero_control']); ?>' class="btn btn-eliminar">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron alumnos en la base de datos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
