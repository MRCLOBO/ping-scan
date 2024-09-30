<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/seguridad_autenticacion/controlador.php';

session_start();
$user = json_decode(json_encode($_SESSION['usuario']));

 //Verificar si el usuario es administrador
    
if (!isset($_SESSION['usuario']) || $user->rol !== 'admin') {
    header("Location: ../../public/login.php");
    exit();
}


$conexion = new Conectar();
$conn = $conexion->getConexion();

$controlador = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controlador->handleRequest();
}

$userToEdit = null;
if (isset($_GET['edit_user'])) {
    $userToEdit = $controlador->getUserToEdit($_GET['edit_user']);
}

$users = $controlador->getAllUsers();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
</head>

<body class="bg-dark text-light">
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/componentes/navbar.php'?>



    
<!-- Inicio del div CONTAINER -->   
<div class="container p-5" >
    <h2 class="text-center">Gestión de Usuarios</h2>

    <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!--Inicio del div ROW que permite separar los elementos en columnas responsivas -->
        <div class="row">
    <!-- Formulario para agregar usuario
    Separacion del formulario con la lista desplegada en el navegador
    para seguir un mejor orden estetico V 1.1.0 
    -->
        <div class="col-md-3">
            <div class="card bg-primary border-light p-3"><!-- toda la seccion del formulario se tomara como una CARD -->
    <h3 class="card card-header text-dark border-0" >Agregar Usuario</h3>
<hr class="bg-dark"/>
    <form method="POST" action="">
        <div>
        <label for="usuario" class="text-dark ">Usuario:</label>
        <br>
        <input type="text" id="usuario" name="usuario" style="width:95%" required class="mb-3">
        </div>
        <div>
        <label for="contrasena" class="text-dark">Contraseña:</label>
        <br>
        <input type="password" id="contrasena" name="contrasena" style="width:95%" required class="mb-3">
        </div>
        <label for="rol" class="text-dark">Rol:</label>
        <select id="rol" name="rol" required class="mb-3">
            <option value="admin">Administrador</option>
            <option value="user">Usuario</option>
        </select>
        <br>
        <div class="card-footer text-center">
        <button class="btn btn-dark" type="submit" name="add_user">Agregar Usuario</button>
    </div>
    </form>
    </div> <!-- Final de la CARD -->
        </div> <!-- Final de la primera columna donde va el formulario de agregacion -->


    <!-- Formulario para editar usuario
      -->
    <?php if ($userToEdit): ?>
        <div class="editar-fondo">
            <div class="card bg-warning editar-card border-dark text-dark">
                <div class="card-header text-center">
        <h3>Editar Usuario</h3>
                </div>
                
        <form method="POST" action="">
        <div class="card-body">    
        <input type="hidden" name="id_usuarios" value="<?php echo htmlspecialchars($userToEdit['id_usuarios']); ?>">
            <label for="edit_username">Usuario:</label>
    </br>
            <input type="text" id="edit_username" 
            name="usuario" class="mb-3" style="width:85%;"
            value="<?php echo htmlspecialchars($userToEdit['usuario']); ?>" required>
            <br>
            <label for="edit_role">Rol:</label>
            </br>
            <select id="edit_role" name="rol" class="mb-3" style="width:85%;" required>
                <option value="admin" <?php echo $userToEdit['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?php echo $userToEdit['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
            </select>
            <br>
    </div>
    <div class="card-footer text-center">
            <button type="submit" name="edit_user"
            class="btn btn-dark"
            >Actualizar Usuario</button>
    </div>
        </form>
        </div>
        </div>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
     <div class="col-md-9 text-center">
    <h3>Lista de Usuarios</h3>
        <div class="p-3 "> <!-- Correcion de la tabla con el espacio entre los elementos-->
    <table  class="tables table-bordered w-100 text-center">
        <thead >
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td style="padding:5px;"><?php echo htmlspecialchars($row['id_usuarios']); ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                    <td>
                        <a class="tabla-opcion-admin" href="?edit_user=<?php echo htmlspecialchars($row['id_usuarios']); ?>">
                        <img style="filter:invert();" src="../../public/media/imagenes/editar.png" alt="Editar">Editar</a>
                        
                        <a class="tabla-opcion-admin" href="?delete_user=<?php echo htmlspecialchars($row['id_usuarios']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');"><img src="../../public/media/imagenes/usuario.png" alt="Eliminar">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div> <!-- Fin de la correcion del espacio de la tabla con los elementos -->
    </div> <!-- Final de la tabla de usuarios mostrados en pantalla -->
    </div> <!-- Final del div ROW -->
    </div><!-- Final del div CONTAINER -->
</body>


</html>