<?php 
 //Conectar a la BD
 require_once('../../config/conectar.php');
 $conexion = new Conectar();
 $conn = $conexion->getConexion();
 //Llamar al controlador
 require './controlador.php';
 $controlador = new ControladorDispositivos($conn);

$id_dispositivos = $_POST['id_dispositivos'];
$ip1 = $_POST['ip1'];
$ip2 = $_POST['ip2'];
$ip3 = $_POST['ip3'];
$ip4 = $_POST['ip4'];
$nombre_equipo = $_POST['nombre_equipo'];

$controlador->editarDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo);

    header("Location: vista.php");
    die();



?>