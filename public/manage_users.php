<?php /*
session_start();
require '../config/conectar.php';
require '../modules/seguridad_autenticacion/controller.php';



// Verificar si el usuario es administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: no_access.php");
    exit();
}

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Instanciar el controlador
$userController = new UserController($conn);

// Manejar la solicitud
$userController->handleRequest();

// Obtener la lista de usuarios
$result = $controller->getAllUsers();

// Obtener datos para la vista
$userToEdit = null;
if (isset($_GET['edit_user'])) {
    $id = $_GET['edit_user'];
    $userToEdit = $userController->getUserToEdit($id);
}

// Incluir la vista
include '../modules/seguridad_autenticacion/view.php';*/
?>
