<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorDispositivos($conn);

    session_start();
    var_dump($_POST);

    $locales = $_POST['locales'];
    $tipoDispositivos = $_POST['tipo_dispositivos'];

    

    ?>
