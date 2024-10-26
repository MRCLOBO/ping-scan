<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorLocales($conn);
    
    session_start();
    $denominacion = $_POST['denominacion'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $ip3 = $_POST['ip3'];

    $patron_texto="/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ\s]+$/";
    $patron_texto_numero = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ1234567890\s]+$/";
    $patron_numero = "/[0-9]/";
    if( preg_match($patron_texto_numero, $denominacion) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo numeros y letras para el nombre del local!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else if(preg_match($patron_texto, $ciudad) !== 1){
        $_SESSION['notificacion']="¡Ingrese solamente letras para la ciudad!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else if(preg_match($patron_texto_numero, $direccion) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo numeros y letras para la direccion!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else if(preg_match($patron_numero, $ip3) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo numeros para la direccion IP!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();  
    }else{
        $controlador->añadirLocal($denominacion,$ciudad,$direccion,$ip3);  
        $_SESSION['notificacion']="Local Añadido correctamente";
    }

    # CONDICION ELSE
    # if( preg_match($patron_texto, $direccion) && preg_match($patron_numero, $ip3)){
    # $controlador->añadirLocal($denominacion,$ciudad,$direccion,$ip3);

// Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

header("Location: vista.php");
die();
?>