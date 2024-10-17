<?php 
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/seguridad_autenticacion/controlador.php';

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controller = new ControladorUsuarios($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller->logout();   
}
?>