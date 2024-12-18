// Archivo: validaciones_cambios.js

document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.querySelector('form');

    formulario.addEventListener('submit', function (event) {
        // Obtener valores de los campos
        const numeroControl = document.getElementById('numero_control').value.trim();
        const nombreCompleto = document.getElementById('nombre_completo').value.trim();
        const carrera = document.getElementById('carrera').value;
        const semestre = document.getElementById('semestre').value;
        const fechaNacimiento = document.getElementById('fecha_nacimiento').value;

        // Validar Número de Control (8 caracteres alfanuméricos)
        const numeroControlRegex = /^[a-zA-Z0-9]{8}$/;
        if (!numeroControl) {
            alert('El número de control es obligatorio.');
            event.preventDefault();
            return;
        }
        if (!numeroControlRegex.test(numeroControl)) {
            alert('El número de control debe tener exactamente 8 caracteres alfanuméricos.');
            event.preventDefault();
            return;
        }

        // Validar Nombre Completo (solo letras y espacios)
        const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!nombreCompleto) {
            alert('El nombre completo es obligatorio.');
            event.preventDefault();
            return;
        }
        if (!nombreRegex.test(nombreCompleto)) {
            alert('El nombre completo solo puede contener letras y espacios.');
            event.preventDefault();
            return;
        }
        if (nombreCompleto.length < 3) {
            alert('El nombre completo debe tener al menos 3 caracteres.');
            event.preventDefault();
            return;
        }

        // Validar Carrera (debe estar dentro de las opciones válidas)
        const carrerasValidas = ["ISC", "IM", "LA", "IIA", "CP"];
        if (!carrerasValidas.includes(carrera)) {
            alert('Selecciona una carrera válida.');
            event.preventDefault();
            return;
        }

        // Validar Semestre
        if (!semestre || semestre < 1 || semestre > 12) {
            alert('Selecciona un semestre válido entre 1 y 12.');
            event.preventDefault();
            return;
        }

        // Validar Fecha de Nacimiento
        if (!fechaNacimiento) {
            alert('La fecha de nacimiento es obligatoria.');
            event.preventDefault();
            return;
        }
        const fechaActual = new Date();
        const fechaIngresada = new Date(fechaNacimiento);
        if (isNaN(fechaIngresada.getTime())) {
            alert('La fecha de nacimiento no es válida.');
            event.preventDefault();
            return;
        }
        if (fechaIngresada >= fechaActual) {
            alert('La fecha de nacimiento debe ser anterior a la fecha actual.');
            event.preventDefault();
            return;
        }

        // Mostrar mensaje de éxito
        alert('El alumno se ha modificado correctamente.');
    });
});
