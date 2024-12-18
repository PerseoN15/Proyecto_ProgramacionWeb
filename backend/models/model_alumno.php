<?php
class Alumno {
    private $numControl;
    private $nombre;
    private $primerAp;
    private $segundoAp;
    private $fechaNacimiento;
    private $semestre;
    private $carrera;
    private $tutor;
    private $enRiesgo; 

    public function __construct($numControl = "", $nombre = "", $primerAp = "", $segundoAp = "", $fechaNacimiento = "", $semestre = 0, $carrera = "", $tutor = "", $enRiesgo = "No") {
        $this->numControl = $numControl;
        $this->nombre = $nombre;
        $this->primerAp = $primerAp;
        $this->segundoAp = $segundoAp;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->semestre = $semestre;
        $this->carrera = $carrera;
        $this->tutor = $tutor;
        $this->enRiesgo = $enRiesgo;
    }

    // Getters y Setters
    public function getNumControl() {
        return $this->numControl;
    }

    public function setNumControl($numControl) {
        $this->numControl = $numControl;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getPrimerAp() {
        return $this->primerAp;
    }

    public function setPrimerAp($primerAp) {
        $this->primerAp = $primerAp;
    }

    public function getSegundoAp() {
        return $this->segundoAp;
    }

    public function setSegundoAp($segundoAp) {
        $this->segundoAp = $segundoAp;
    }

    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getSemestre() {
        return $this->semestre;
    }

    public function setSemestre($semestre) {
        $this->semestre = $semestre;
    }

    public function getCarrera() {
        return $this->carrera;
    }

    public function setCarrera($carrera) {
        $this->carrera = $carrera;
    }

    public function getTutor() {
        return $this->tutor;
    }

    public function setTutor($tutor) {
        $this->tutor = $tutor;
    }

    public function getEnRiesgo() {
        return $this->enRiesgo;
    }

    public function setEnRiesgo($enRiesgo) {
        $this->enRiesgo = $enRiesgo;
    }
}
?>
