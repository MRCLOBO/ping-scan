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
$usuario_local= $_POST['usuario_local'];


if($rol === "user"){
    $controlador->editarUsuario($id_usuarios,$usuario,$nombre,$rol);
    $controlador->editarUsuarioLocal($usuario_local,$usuario);
}
if($_POST['rol'] == "admin" || $_POST['rol'] == "tecnico"){
    $controlador->editarUsuario($id_usuarios,$usuario,$nombre,$rol);
    $controlador->eliminarUsuarioLocal($usuario);
}
session_start();
$_SESSION['notificacion']="Usuario Modificado";
    header("Location: vista.php");
    die();
?>