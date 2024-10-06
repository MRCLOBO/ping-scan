<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorUsuarios($conn);

    $id_usuarios = $_POST['id_usuarios'];
    $rol = $_POST['rol'];
    $usuario = $_POST['usuario'];

    if($_POST['rol'] == 'user'){
        $controlador->eliminarUsuarioLocal($usuario);
    }
    $controlador->eliminarUsuario($id_usuarios);

    die();
?>