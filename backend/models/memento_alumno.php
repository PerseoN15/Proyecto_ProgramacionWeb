<?php
class Memento {
    private $alumno;

    public function __construct($alumno) {
        $this->alumno = $alumno;
    }

    public function getAlumno() {
        return $this->alumno;
    }
}

class MementoManager {
    public static function guardarMemento($alumno) {
        // Guarda el alumno eliminado en la sesión.
        $_SESSION['memento'] = new Memento($alumno);
    }

    public static function obtenerMemento() {
        // Recupera el alumno eliminado si existe en la sesión.
        return isset($_SESSION['memento']) ? $_SESSION['memento']->getAlumno() : null;
    }

    public static function borrarMemento() {
        // Borra el memento de la sesión.
        unset($_SESSION['memento']);
    }
}
?>
