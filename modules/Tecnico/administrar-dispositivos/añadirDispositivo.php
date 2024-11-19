<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorDispositivos($conn);

    session_start();

    $ip1 = $_POST['ip1'];
    $ip2 = $_POST['ip2'];
    $ip3 = $_POST['ip3'];
    $ip4 = $_POST['ip4'];
    $nombre_equipo = $_POST['nombre_equipo'];

    $patron_texto="/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ\s]+$/";
    $patron_texto_numero = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ1234567890\s]+$/";
    $patron_numero = "/[0-9]/";
    if( preg_match($patron_numero, $ip1) !== 1 && preg_match($patron_numero, $ip2) !== 1 && preg_match($patron_numero, $ip3) !== 1 && preg_match($patron_numero, $ip4) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo numeros para las IPs!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else{

        $comprobarIP = $controlador->comprobarIP($ip1,$ip2,$ip3,$ip4);
        $comprobarIP2 = $controlador->comprobarIP2($ip2);
        $comprobarIP3 = $controlador->comprobarIP3($ip3);
        if($comprobarIP !== null){
            //redireccionar atras
            $_SESSION['error'] = 
            ['error' => 'ip duplicada',
            'origen' => 'anadir',
            'ip1' => $ip1,
            'ip2' => $ip2,
            'ip3' => $ip3,
            'ip4' => $ip4,
            'nombre_equipo' => $nombre_equipo,];
            header('Location:'.getenv('HTTP_REFERER'));
            die();
                                }       

        if($comprobarIP2 === null){
            //redireccionar atras
            $_SESSION['error'] = 
            ['error' => 'no existe tipo',
            'origen' => 'anadir',
            'ip1' => $ip1,
            'ip2' => $ip2,
            'ip3' => $ip3,
            'ip4' => $ip4,
            'nombre_equipo' => $nombre_equipo,];
            header('Location:'.getenv('HTTP_REFERER'));
            die();
                                }       

        if($comprobarIP3 === null){
        //redireccionar atras
        $_SESSION['error'] = 
        ['error' => 'no existe local',
        'origen' => 'anadir',
        'ip1' => $ip1,
        'ip2' => $ip2,
        'ip3' => $ip3,
        'ip4' => $ip4,
        'nombre_equipo' => $nombre_equipo,];
        header('Location:'.getenv('HTTP_REFERER'));
        die();
        }       
       

        $controlador->añadirDispositivo($ip1,$ip2,$ip3,$ip4,$nombre_equipo);
        $_SESSION['notificacion']= $nombre_equipo." con IP:".$ip1.".".$ip2.".".$ip3.".".$ip4." añadido correctamente";
    }
    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

    header("Location: vista.php");
    die();
?>