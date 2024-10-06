<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorDispositivos($conn);

    $ip1 = $_POST['ip1'];
    $ip2 = $_POST['ip2'];
    $ip3 = $_POST['ip3'];
    $ip4 = $_POST['ip4'];
    $nombre_equipo = $_POST['nombre_equipo'];
    $controlador->añadirDispositivo($ip1,$ip2,$ip3,$ip4,$nombre_equipo);

    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

    header("Location: vista.php");
    die();
?>