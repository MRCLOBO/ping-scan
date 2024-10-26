<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorUsuarios($conn);

    session_start();

    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $contrasena = $_POST['contrasena'];
    $usuario_local = $_POST['usuario_local'];

    $patron_texto="/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ\s]+$/";
    $patron_texto_numero = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ1234567890\s]+$/";
    $patron_numero = "/[0-9]/";
    
    if( preg_match($patron_texto_numero, $usuario) !== 1){
    $_SESSION['notificacion']="¡Ingrese solo letras y numeros para el usuario!";
    header('Location:'.getenv('HTTP_REFERER'));
    die();
    }else if( preg_match($patron_texto, $nombre) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo letras para el nombre del usuario!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else{
        $controlador->añadirUsuario($usuario,$nombre,$rol,$contrasena);
        if($_POST['rol'] == 'user'){
            $controlador->añadirUsuarioLocal($usuario_local,$usuario);
        };
        $_SESSION['notificacion']="Usuario Añadido";
    }

    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));
    header("Location: vista.php");
    die();
?>