<?php 
    //Conectar a la BD
    require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
    $conexion = new Conectar();
    $conn = $conexion->getConexion();
    //Llamar al controlador
    require './controlador.php';
    $controlador = new ControladorLocales($conn);

    $id_locales = $_POST['id_locales'];
    $denominacion = $_POST['denominacion'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $ip3 = $_POST['ip3'];


    //Eliminar todos los usuarios relacionados al local de la tabla usuarios_local
    $controlador->editarUsuarioLocal($id_locales,$denominacion);
    //Eliminar todos los dispositivos relacionados al local de la tabla dispositivos
    $controlador->editarLocalDeDispositivos($id_locales,$ip3);
    //Se elimina como ultimo el local ya que es una clave de la cual dependen los demas componentes
    $controlador->editarLocal($id_locales,$denominacion,$ciudad,$direccion,$ip3);
    
    header("Location: vista.php");
    die();
?>