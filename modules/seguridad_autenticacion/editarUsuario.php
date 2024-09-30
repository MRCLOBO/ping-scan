<?php 
 //Conectar a la BD
 require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
 $conexion = new Conectar();
 $conn = $conexion->getConexion();
 //Llamar al controlador
 require './controlador.php';
 $controlador = new ControladorUsuarios($conn);

$id_usuarios = $_POST['id_usuarios'];
$usuario = $_POST['usuario'];
$nombre= $_POST['nombre'];
$rol= $_POST['rol'];

$controlador->editarUsuario($id_usuarios,$usuario,$nombre,$rol);

    header("Location: vista.php");
    die();



?>