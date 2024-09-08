<?php
session_start();

// Verificar si el usuario está autenticado
/*if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';*/
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

echo "Bienvenido, " . $_SESSION['usuario'] . "!";

if ($_SESSION['rol'] == 'admin') {
    echo "<a href='../modules/seguridad_autenticacion/view.php'>Gestión de Usuarios</a>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenido al Dashboard</h2>
    <!-- Aquí van otras secciones del dashboard -->

    <?php if ($_SESSION['rol'] == 'user'/*$role === 'admin'*/): ?>
        <h3>Opciones de Administrador</h3>
        <ul>
            <li><a href="manage_users.php">Gestión de Usuarios</a></li>
        </ul>
    <?php endif; ?>
    
</body>
</html>

