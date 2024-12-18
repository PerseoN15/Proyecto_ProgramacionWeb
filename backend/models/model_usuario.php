<?php
class Usuario {
    private $nombreUsuario; // Nombre del usuario
    private $password;      // Contraseña del usuario
    private $privilegio;    // Privilegio del usuario

 
    public function __construct($nombreUsuario = "", $password = "", $privilegio = "") {
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->privilegio = $privilegio;
    }

    // Métodos getter y setter para nombreUsuario
    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    // Métodos getter y setter para password
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // Métodos getter y setter para privilegio
    public function getPrivilegio() {
        return $this->privilegio;
    }

    public function setPrivilegio($privilegio) {
        $this->privilegio = $privilegio;
    }
}
?>
