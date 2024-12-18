// Función para redirigir a bajas_cambios.php con los datos del alumno seleccionado
function redirigirABajasCambios() {
    document.querySelectorAll(".btn-seleccionar").forEach((button) => {
        button.addEventListener("click", () => {
            try {
                const alumno = JSON.parse(button.dataset.alumno);

                console.log("Datos del alumno:", alumno); // Depuración

                if (!alumno.nombre_completo) {
                    alert("Error: Falta el campo 'nombre_completo' en los datos del alumno.");
                    return;
                }

                const params = new URLSearchParams(alumno).toString();
                const url = `bajas_cambios.php?${params}`;
                console.log("Redirigiendo a:", url); // Depuración

                window.location.href = url;
            } catch (error) {
                console.error("Error al procesar la redirección:", error);
                alert("Hubo un error al procesar la redirección. Verifica la consola para más detalles.");
            }
        });
    });
}

// Función para llenar los campos del formulario en bajas_cambios.php
function llenarFormularioBajasCambios() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        document.querySelector("#numero_control").value = urlParams.get("numero_control") || "";
        document.querySelector("#nombre_completo").value = urlParams.get("nombre_completo") || "";
        document.querySelector("#carrera").value = urlParams.get("carrera") || "";
        document.querySelector("#semestre").value = urlParams.get("semestre") || "";
        document.querySelector("#fecha_nacimiento").value = urlParams.get("fecha_nacimiento") || "";
    } catch (error) {
        console.error("Error al llenar los campos del formulario:", error);
        alert("Hubo un error al cargar los datos del formulario. Verifica la consola para más detalles.");
    }
}

// Llamar automáticamente a la función de redirección si estamos en la página correspondiente
if (document.querySelectorAll(".btn-seleccionar").length > 0) {
    redirigirABajasCambios();
}

if (document.querySelector("#numero_control")) {
    llenarFormularioBajasCambios();
}
