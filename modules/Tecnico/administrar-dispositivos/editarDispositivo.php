<?php 
 //Conectar a la BD
 require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
 $conexion = new Conectar();
 $conn = $conexion->getConexion();
 //Llamar al controlador
 require './controlador.php';
 $controlador = new ControladorDispositivos($conn);

 session_start();

 $id_dispositivos = $_POST['id_dispositivos'];
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
     $controlador->editarDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo);
     $_SESSION['notificacion']="Dispositivo ".$ip1.".".$ip2.".".$ip3.".".$ip4." editado correctamente";
 }
 
     header("Location: vista.php");
     die();
 
 
 
?>