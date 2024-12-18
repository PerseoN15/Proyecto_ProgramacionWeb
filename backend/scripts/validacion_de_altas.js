document.addEventListener("DOMContentLoaded", () => {
    const numeroControlInput = document.getElementById("numero_control");
    const nombreCompletoInput = document.getElementById("nombre_completo");
    const logError = document.getElementById("log-error");

    // Función para mostrar un mensaje en el log
    function mostrarLogError(mensaje) {
        logError.style.display = "block";
        logError.textContent = mensaje;
    }

    // Función para ocultar el mensaje de error
    function ocultarLogError() {
        logError.style.display = "none";
        logError.textContent = "";
    }

    // Validar que solo se ingresen números en el campo Número de Control
    numeroControlInput.addEventListener("input", () => {
        const valor = numeroControlInput.value;
        numeroControlInput.value = valor.replace(/[^0-9]/g, ""); // Reemplaza cualquier carácter que no sea número
        ocultarLogError();
    });

    // Validar que solo se ingresen letras y espacios en el campo Nombre Completo
    nombreCompletoInput.addEventListener("input", () => {
        const valor = nombreCompletoInput.value;
        nombreCompletoInput.value = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ""); // Reemplaza cualquier carácter que no sea letra o espacio
        ocultarLogError();
    });

    // Validar Número de Control para comprobar si ya existe
    numeroControlInput.addEventListener("blur", async () => {
        const numeroControl = numeroControlInput.value.trim();
        if (numeroControl.length === 8) {
            const existe = await verificarNumeroControl(numeroControl);
            if (existe) {
                mostrarLogError("El número de control ya está registrado.");
            } else {
                ocultarLogError();
            }
        }
    });

    // Validar formulario al intentar enviarlo
    const formulario = document.querySelector("form");
    formulario.addEventListener("submit", async (event) => {
        let valido = true;

        // Validar Número de Control
        if (numeroControlInput.value.length !== 8) {
            mostrarLogError("El número de control debe tener exactamente 8 dígitos.");
            valido = false;
        } else {
            const existe = await verificarNumeroControl(numeroControlInput.value.trim());
            if (existe) {
                mostrarLogError("El número de control ya está registrado.");
                valido = false;
            }
        }

        // Validar Nombre Completo
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombreCompletoInput.value) || nombreCompletoInput.value.trim().length < 3) {
            mostrarLogError("El nombre completo debe contener solo letras y espacios, con al menos 3 caracteres.");
            valido = false;
        }

        // Validar Carrera
        const carreraInput = document.getElementById("carrera");
        if (!carreraInput.value) {
            mostrarLogError("Debes seleccionar una carrera.");
            valido = false;
        }

        // Validar Semestre
        const semestreInput = document.getElementById("semestre");
        if (!semestreInput.value) {
            mostrarLogError("Debes seleccionar un semestre.");
            valido = false;
        }

        // Validar Fecha de Nacimiento
        const fechaNacimientoInput = document.getElementById("fecha_nacimiento");
        if (!fechaNacimientoInput.value) {
            mostrarLogError("Debes ingresar una fecha de nacimiento.");
            valido = false;
        }

        // Si no pasa las validaciones, evitar el envío
        if (!valido) {
            event.preventDefault();
        } else {
            ocultarLogError();
        }
    });

    // Función para verificar si el Número de Control ya está registrado
    async function verificarNumeroControl(numeroControl) {
        try {
            const response = await fetch("../../backend/controllers/verificar_numero_control.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `numero_control=${encodeURIComponent(numeroControl)}`,
            });

            if (!response.ok) {
                throw new Error("Error al verificar el número de control.");
            }

            const data = await response.json();
            return data.existe; // true si el número de control ya existe, false en caso contrario
        } catch (error) {
            console.error("Error en la verificación del número de control:", error);
            mostrarLogError("Error al verificar el número de control. Intenta nuevamente.");
            return true; // Asume que existe para prevenir errores
        }
    }
});
