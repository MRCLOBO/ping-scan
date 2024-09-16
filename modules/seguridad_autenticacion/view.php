<?php
require '../../config/conectar.php';
require 'controller.php';

session_start();
$user = json_decode(json_encode($_SESSION['user']));

// Verificar si el usuario es administrador
if (!isset($_SESSION['user']) || $user->rol !== 'admin') {
    header("Location: login.php");
    exit();
}

$conexion = new Conectar();
$conn = $conexion->getConexion();

$controller = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->handleRequest();
}

$userToEdit = null;
if (isset($_GET['edit_user'])) {
    $userToEdit = $controller->getUserToEdit($_GET['edit_user']);
}

$users = $controller->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
</head>

<body>
    <h2>Gestión de Usuarios</h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulario para agregar usuario -->
    <h3>Agregar Usuario</h3>
    <form method="POST" action="">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="admin">Administrador</option>
            <option value="tec">Tecnico</option>
            <option value="user">Usuario</option>
        </select>
        <br>
        <button type="submit" name="add_user">Agregar Usuario</button>
    </form>

    <!-- Formulario para editar usuario -->
    <?php if ($userToEdit): ?>
        <h3>Editar Usuario</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userToEdit['id_usuarios']); ?>">
            <label for="edit_username">Usuario:</label>
            <input type="text" id="edit_username" name="username" value="<?php echo htmlspecialchars($userToEdit['username']); ?>" required>
            <br>
            <label for="edit_role">Rol:</label>
            <select id="edit_role" name="role" required>
                <option value="admin" <?php echo $userToEdit['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?php echo $userToEdit['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
            </select>
            <br>
            <button type="submit" name="edit_user">Actualizar Usuario</button>
        </form>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <h3>Lista de Usuarios</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_usuarios']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                    <td>
                        <a href="?edit_user=<?php echo htmlspecialchars($row['id_usuarios']); ?>">Editar</a>
                        <a href="?delete_user=<?php echo htmlspecialchars($row['id_usuarios']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>