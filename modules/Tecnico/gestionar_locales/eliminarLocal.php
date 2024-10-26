<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorLocales($conn);

    session_start();

    $id_locales = $_POST['id_locales'];
    $ip3 = $_POST['ip3'];
    $denominacion = $_POST['denominacion'];

    //Eliminar a todos los usuarios relacionados con el local
    $usuarios = $controlador->getUsuariosDelLocal($denominacion);
    
     foreach($usuarios as $row){
        $controlador->eliminarUsuario($row['usuarios_id_usuarios']);
    }

    //Eliminar todos los usuarios relacionados al local de la tabla usuarios_local
    $controlador->eliminarUsuarioDeLocal($denominacion);
    //Eliminar todos los dispositivos relacionados al local de la tabla dispositivos
    $controlador->eliminarDispositivosDeLocal($ip3);
    //Se elimina como ultimo el local ya que es una clave de la cual dependen los demas componentes
    $controlador->eliminarLocal($id_locales);
    $_SESSION['notificacion']= $denominacion." Eliminado correctamente";
    header("Location: vista.php");
    die();
?>