<?php
include '../config/conectar.php';

$conexion = new conectar();
$conexion = $conexion->getConexion();

// Verificar si existe un administrador
$checkAdmin = $conexion->query("SELECT * FROM usuarios WHERE rol = 'admin' LIMIT 1");

if ($checkAdmin->num_rows > 0) {
    // Si ya existe un administrador, redirigir al login
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validar campos (agrega más validaciones según tus necesidades)
    if (empty($username) || empty($password) || empty($role)) {
        echo "Por favor, complete todos los campos.";
    } else {
        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, contrasena, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            echo "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST" action="register.php">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="admin">Administrador</option>
        </select>
        <br>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
