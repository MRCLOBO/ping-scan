<?php
//echo "Iniciando conectar.php";
class Conectar {
    private $servidor = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $bd = "ping_scan";
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->bd);

        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
        
    }

    public function getConexion() {
        return $this->conexion;
        
    }

}

?>

