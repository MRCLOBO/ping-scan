<?php 
    //Conectar a la BD
    require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorTipoDispositivo($conn);
    session_start();
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

            if($comprobarTipo !== null){
                //redireccionar atras
                $_SESSION['error'] = 
                ['error' => 'tipo duplicado',
                'origen' => 'anadir',
                'equipo' => $equipo,
                'ip2' => $ip2,];
                header('Location:'.getenv('HTTP_REFERER'));
                die();
                }       
    
            if($comprobarIP !== null){
                //redireccionar atras
                $_SESSION['error'] = 
                ['error' => 'ip duplicada',
                'origen' => 'anadir',
                'equipo' => $equipo,
                'ip2' => $ip2,];
                
                header('Location:'.getenv('HTTP_REFERER'));
                die();
                
                                    }       
    

            $controlador->añadirTipoDispositivo($equipo,$ip2);  
            $_SESSION['notificacion']="Tipo de dispositivos añadido correctamente";
        }

        # CONDICION ELSE
        # if( preg_match($patron_texto, $direccion) && preg_match($patron_numero, $ip3)){
        # $controlador->añadirLocal($denominacion,$ciudad,$direccion,$ip3);

    // Codigo para ir a la pagina anterior: header('Location:' . getenv('HTTP_REFERER'));

    header("Location: vista.php");
    die();
?>