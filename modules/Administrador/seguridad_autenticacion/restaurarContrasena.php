<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorUsuarios($conn);

    $id_usuarios = $_POST['id_usuarios'];

    $controlador->añadirUsuario($usuario,$nombre,$rol,$contrasena);
    $controlador->restaurarContrasena($id_usuarios);
    header("Location: vista.php");
    die();
?>