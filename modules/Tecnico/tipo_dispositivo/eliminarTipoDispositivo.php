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
    $ip2 = $_POST['ip2'];
    $equipo = $_POST['equipo'];

    //Eliminar a todos los usuarios relacionados con el local
    $dispositivos = $controlador->getDispositivosDeTipo($ip2);
    
     foreach($dispositivos as $row){
        $controlador->eliminarDispositivo($row['id_dispositivos']);
    }

    //Se elimina como ultimo el local ya que es una clave de la cual dependen los demas componentes
    $controlador->eliminarTipo($id_tipo_dispositivo);

    $_SESSION['notificacion']= $equipo." Eliminado correctamente";
    header("Location: vista.php");
    die();
?>