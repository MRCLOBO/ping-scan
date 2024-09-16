<?php
require '../config/conectar.php';

$conexion = new Conectar();
$conn = $conexion->getConexion();

if ($conn) {
    echo "Conexión exitosa";
} else {
    echo "Conexión fallida";
}
?>

