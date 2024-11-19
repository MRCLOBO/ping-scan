<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorTipoDispositivo($conn);
    session_start();

    $id_tipo_dispositivo = $_POST['id_tipo_dispositivo'];
    $equipo = $_POST['equipo'];
    $ip2 = $_POST['ip2'];
    
    $patron_texto="/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ\s]+$/";
    $patron_texto_numero = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ1234567890\s]+$/";
    $patron_numero = "/[0-9]/";

    if( preg_match($patron_texto, $equipo) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo letras para el tipo de dispositivo!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();
    }else if(preg_match($patron_numero, $ip2) !== 1){
        $_SESSION['notificacion']="¡Ingrese solo numeros para la VLAN!";
        header('Location:'.getenv('HTTP_REFERER'));
        die();  
    }else{
        $comprobarIP = $controlador->comprobarIP($ip2);
        $comprobarTipo = $controlador->comprobarTipo($equipo);

        if($comprobarTipo !== null && $comprobarTipo['id_tipo_dispositivo'] != $id_tipo_dispositivo){
            //redireccionar atras
            $_SESSION['error'] = 
            ['error' => 'tipo duplicado',
            'origen' => 'editar',
            'id'=> $id_tipo_dispositivo,
            'equipo' => $equipo,
            'ip2' => $ip2,];
            header('Location:'.getenv('HTTP_REFERER'));
            die();
            }       

        if($comprobarIP !== null && $comprobarIP['id_tipo_dispositivo'] != $id_tipo_dispositivo){
            //redireccionar atras
            $_SESSION['error'] = 
            ['error' => 'ip duplicada',
            'origen' => 'editar',
            'id' => $id_tipo_dispositivo,
            'equipo' => $equipo,
            'ip2' => $ip2,];
            
            header('Location:'.getenv('HTTP_REFERER'));
            die();
            
                                } 
                                
        $controlador->editarDispositivosConTipo($id_tipo_dispositivo,$ip2);
        $controlador->editarTipoDispositivo($id_tipo_dispositivo,$equipo,$ip2);    
        $_SESSION['notificacion']= $equipo." editado correctamente";
    }
    
    header("Location: vista.php");
    die();
?>