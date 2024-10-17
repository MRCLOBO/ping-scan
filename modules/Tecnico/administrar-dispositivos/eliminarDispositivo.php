<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorDispositivos($conn);

    $id_dispositivos = $_POST['id_dispositivos'];
    $controlador->eliminarDispositivo($id_dispositivos);

    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

    header("Location: vista.php");
    die();
?>