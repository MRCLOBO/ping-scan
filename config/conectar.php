<?php
//echo "Iniciando conectar.php";
//para uso en todo el codigo de la hora de Paraguay
date_default_timezone_set('America/Asuncion');
class Conectar {
    private $servidor = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $bd = "ping_scan";
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->bd);

        /*if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
        else {
            echo "Conexión exitosa";
        }*/
    }

    public function getConexion() {
        return $this->conexion;
        
    }

}

?>

