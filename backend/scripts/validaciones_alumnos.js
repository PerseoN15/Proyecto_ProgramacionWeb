document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    if (!form) return;

    form.addEventListener("submit", (event) => {
        limpiarMensajes();

        const numeroControlInput = document.querySelector("#numero_control");
        const nombreCompletoInput = document.querySelector("#nombre_completo");
        const carreraInput = document.querySelector("#carrera");
        const semestreInput = document.querySelector("#semestre");
        const fechaNacimientoInput = document.querySelector("#fecha_nacimiento");

        let valido = true;

        // Validar número de control
        if (!/^\d{8}$/.test(numeroControlInput.value.trim())) {
            mostrarMensaje("El número de control debe contener exactamente 8 números.", "error");
            numeroControlInput.classList.add("input-error");
            valido = false;
        } else {
            numeroControlInput.classList.remove("input-error");
        }

        // Validar nombre completo
        if (!nombreCompletoInput.value.trim() || !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombreCompletoInput.value.trim())) {
            mostrarMensaje("El nombre completo es obligatorio y solo puede contener letras y espacios.", "error");
            nombreCompletoInput.classList.add("input-error");
            valido = false;
        } else {
            nombreCompletoInput.classList.remove("input-error");
        }

        // Validar carrera
        if (!carreraInput.value) {
            mostrarMensaje("Debe seleccionar una carrera.", "error");
            carreraInput.classList.add("input-error");
            valido = false;
        } else {
            carreraInput.classList.remove("input-error");
        }

        // Validar semestre
        if (!semestreInput.value) {
            mostrarMensaje("Debe seleccionar un semestre.", "error");
            semestreInput.classList.add("input-error");
            valido = false;
        } else {
            semestreInput.classList.remove("input-error");
        }

        // Validar fecha de nacimiento
        if (!fechaNacimientoInput.value.trim()) {
            mostrarMensaje("La fecha de nacimiento es obligatoria.", "error");
            fechaNacimientoInput.classList.add("input-error");
            valido = false;
        } else {
            fechaNacimientoInput.classList.remove("input-error");
        }

        if (!valido) {
            event.preventDefault();
        }
    });

    // Validar que el campo de nombre completo no acepte números
    const nombreCompletoInput = document.querySelector("nombre");
    if (nombreCompletoInput) {
        nombreCompletoInput.addEventListener("keypress", (e) => {
            const charCode = e.which || e.keyCode;

            // Permitir teclas especiales como Backspace, Tab, y flechas
            const teclasEspeciales = [8, 9, 37, 38, 39, 40, 46];
            const esTeclaEspecial = teclasEspeciales.includes(charCode);

            // Validar que la tecla no sea un número y permitir solo letras
            const esLetra = 
                (charCode >= 65 && charCode <= 90) || // Letras mayúsculas (A-Z)
                (charCode >= 97 && charCode <= 122) || // Letras minúsculas (a-z)
                charCode === 32; // Espacio

            if (!esLetra && !esTeclaEspecial) {
                e.preventDefault();
                console.log(`Carácter bloqueado: ${charCode}`);
            }
        });

        // Eliminar caracteres inválidos si ya están en el campo
        nombreCompletoInput.addEventListener("input", () => {
            nombreCompletoInput.value = nombreCompletoInput.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
        });
    }

    // Bloquear letras en el campo de número de control
    const numeroControlInput = document.querySelector("numero_control");
    if (numeroControlInput) {
        numeroControlInput.addEventListener("keydown", (e) => {
            const charCode = e.which || e.keyCode;

            // Permitir teclas especiales como Backspace, Tab, y flechas
            const teclasEspeciales = [8, 9, 37, 38, 39, 40, 46];
            const esTeclaEspecial = teclasEspeciales.includes(charCode);

            // Validar que la tecla sea solo un número
            const esNumero = charCode >= 48 && charCode <= 57; // Números (0-9)

            if (!esNumero && !esTeclaEspecial) {
                e.preventDefault();
                console.log(`Carácter bloqueado: ${charCode}`);
            }
        });

        // Eliminar caracteres inválidos si ya están en el campo
        numeroControlInput.addEventListener("input", () => {
            numeroControlInput.value = numeroControlInput.value.replace(/[^0-9]/g, "");
        });
    }
});

// Función para mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    const mensajeDiv = document.createElement("div");
    mensajeDiv.classList.add("mensaje", tipo === "error" ? "mensaje-error" : "mensaje-exito");
    mensajeDiv.innerText = mensaje;
    document.querySelector(".form-container").prepend(mensajeDiv);

    setTimeout(() => {
        mensajeDiv.remove();
    }, 5000);
}

// Función para limpiar mensajes previos
function limpiarMensajes() {
    document.querySelectorAll(".mensaje").forEach((mensaje) => mensaje.remove());
}
