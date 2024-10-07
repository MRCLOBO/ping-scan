<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorLocales($conn);

        $denominacion = $_POST['denominacion'];
        $ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];
        $ip3 = $_POST['ip3'];

        $controlador->añadirLocal($denominacion,$ciudad,$direccion,$ip3);

    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

    header("Location: vista.php");
    die();
?>