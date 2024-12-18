// Validar que el campo solo acepte letras
function validarSoloLetrasModal(e) {
    const charCode = e.which || e.keyCode;
    const validChar = 
        charCode === 32 || // Espacio
        (charCode >= 65 && charCode <= 90) || // Letras mayúsculas (A-Z)
        (charCode >= 97 && charCode <= 122); // Letras minúsculas (a-z)

    if (!validChar) {
        e.preventDefault();
        console.log(`Carácter bloqueado: ${charCode}`);
    }
}

// Validar que el campo solo acepte números
function validarSoloNumerosModal(e) {
    const charCode = e.which || e.keyCode;
    const validChar = charCode >= 48 && charCode <= 57; // Números 0-9

    if (!validChar) {
        e.preventDefault();
        console.log(`Carácter bloqueado: ${charCode}`);
    }
}

// Eliminar caracteres no válidos (solo letras)
function eliminarCaracteresInvalidosLetras(field) {
    field.value = field.value.replace(/[^a-zA-Z\s]/g, '');
}

// Eliminar caracteres no válidos (solo números)
function eliminarCaracteresInvalidosNumeros(field) {
    field.value = field.value.replace(/[^0-9]/g, '');
}

// Aplicar las validaciones a los campos del modal
document.addEventListener("DOMContentLoaded", () => {
    // Seleccionar campos del modal
    const modalFields = {
        letras: ["nombre", "primerAp", "segundoAp"], // Campos de texto
        numeros: ["numControl", "semestre"] // Campos numéricos
    };

    // Validar solo letras
    modalFields.letras.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            field.addEventListener("keydown", validarSoloLetrasModal);
            field.addEventListener("input", () => eliminarCaracteresInvalidosLetras(field));
        }
    });

    // Validar solo números
    modalFields.numeros.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            field.addEventListener("keydown", validarSoloNumerosModal);
            field.addEventListener("input", () => eliminarCaracteresInvalidosNumeros(field));
        }
    });
});
