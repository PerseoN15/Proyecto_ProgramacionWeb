<?php
include_once('../models/model_alumno.php');
include_once('../controllers/controller_alumno.php');

class Fachada_Alumno {
    private $alumnoDAO;

    public function __construct() {
        $this->alumnoDAO = new AlumnoDAO();
    }

    public function registrarAlumno($alumno) {
        // Intentar registrar al alumno directamente
        return $this->alumnoDAO->agregarAlumno($alumno);
    }
}
?>
