<?php
session_start();
require '../config/conectar.php';
require '../modules/seguridad_autenticacion/controller.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conexion = new Conectar();
$conn = $conexion->getConexion();

// Agregar usuario
if (isset($_POST['add_user'])) {
    var_dump($_POST); // Verifica si los datos están llegando correctamente
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($password) && !empty($role)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
        if ($stmt === false) {
            echo "Error preparando la consulta: " . $conn->error;
        }

        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            echo "Usuario agregado exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

// Editar usuario
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($id) && !empty($username) && !empty($role)) {
        $stmt = $conn->prepare("UPDATE usuarios SET username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $role, $id);

        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
}

// Eliminar usuario
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];

    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID de usuario inválido.";
    }
}

// Obtener datos del usuario para edición
$userToEdit = null;
if (isset($_GET['edit_user'])) {
    $id = $_GET['edit_user'];
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $userToEdit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Listar usuarios
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h2>Gestión de Usuarios</h2>

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
            <option value="user">Usuario</option>
        </select>
        <br>
        <button type="submit" name="add_user">Agregar Usuario</button>
    </form>

    <!-- Formulario para editar usuario -->
    <?php if ($userToEdit): ?>
        <h3>Editar Usuario</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($userToEdit['id']); ?>">
            <label for="edit_username">Usuario:</label>
            <input type="text" id="edit_username" name="username" value="<?php echo htmlspecialchars($userToEdit['username']); ?>" required>
            <br>
            <label for="edit_role">Rol:</label>
            <select id="edit_role" name="role" required>
                <option value="admin" <?php echo $userToEdit['role'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?php echo $userToEdit['role'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <a href="?edit_user=<?php echo htmlspecialchars($row['id']); ?>">Editar</a>
                        <a href="?delete_user=<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
