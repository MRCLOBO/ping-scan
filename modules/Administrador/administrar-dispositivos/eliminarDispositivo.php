<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorDispositivos($conn);

    session_start();

    $id_dispositivos = $_POST['id_dispositivos'];
    $ip1 = $_POST['ip1'];
    $ip2 = $_POST['ip2'];
    $ip3 = $_POST['ip3'];
    $ip4 = $_POST['ip4'];
    $nombre_equipo = $_POST['nombre_equipo'];
    $controlador->eliminarDispositivo($id_dispositivos);
    $_SESSION['notificacion']= $nombre_equipo." con IP:".$ip1.".".$ip2.".".$ip3.".".$ip4." eliminado correctamente";
    
    header("Location: vista.php");
    die();
?>