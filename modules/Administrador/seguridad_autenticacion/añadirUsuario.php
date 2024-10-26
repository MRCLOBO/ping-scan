<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorUsuarios($conn);

    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $contrasena = $_POST['contrasena'];
    $usuario_local = $_POST['usuario_local'];

    $controlador->añadirUsuario($usuario,$nombre,$rol,$contrasena);

    if($_POST['rol'] == 'user'){
        $controlador->añadirUsuarioLocal($usuario_local,$usuario);
    };
    session_start();
    $_SESSION['notificacion']="Usuario Añadido";
    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));
    header("Location: vista.php");
    die();
?>