<?php
require '../config/conectar.php';
require '../modules/seguridad_autenticacion/controller.php';

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controller = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    header('Content-Type: application/json'); // Indicar que la respuesta es JSON

    if ($controller->login($username, $password)) {
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso', 'redirect' => '../modules/dashboard/DashboardView.php']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nombre de usuario o contraseña incorrectos.']);
    }
    exit(); // Asegúrate de terminar el script después de la respuesta
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/styles.css">    
</head>
<body>
    <div id="error-message" style="color: red;"></div> <!-- Mostrar mensaje de error aquí -->
    
    <form action="login.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
<script src="js/scripts.js"></script> <!-- Enlace a scripts.js -->
</html>
