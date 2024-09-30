<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/seguridad_autenticacion/controlador.php';

session_start();

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controlador = new ControladorUsuarios($conn);

if (!isset($_SESSION['usuario'])) {
    header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/ping-scan/public/login.php');
    exit();
}
else{
    // Enviar los datos necesarios a la vista
    $user = json_decode(json_encode($_SESSION['usuario']));
}

// Si es una solicitud para cerrar sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controlador->logout();
   /* header("Location: ../seguridad_autenticacion/login.php");
    exit();*/
}

require_once 'DashboardView.php';
?>
