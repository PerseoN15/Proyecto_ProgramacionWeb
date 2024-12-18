<?php
class ConexionBDTutorias {
    private static $instancia = null; // Instancia única del Singleton
    private $conexion;

    private $host = "localhost";
    private $port = "3306";
    private $usuario = "root";
    private $password = "";
    private $bd = "proyecto_tutorias";

    // Constructor privado para evitar instancias externas
    private function __construct() {
        try {
            $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->bd;charset=utf8";
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en conexión a la BD: " . $e->getMessage());
        }
    }

    // Método estático para obtener la instancia única
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new ConexionBDTutorias();
        }
        return self::$instancia;
    }

    // Método para obtener la conexión PDO
    public function getConexion() {
        return $this->conexion;
    }

    // Evitar clonación del objeto
    private function __clone() {}

    // Evitar deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia de esta clase.");
    }
}
?>
