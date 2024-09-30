<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require '../config/conectar.php'; 
if (file_exists('../modules/seguridad_autenticacion/controller.php')) {
    require '../modules/seguridad_autenticacion/controller.php';
} else {
    echo "Archivo controller.php no encontrado<br>";
}



session_start();

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controller = new UserController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];
    
    
    if ($controller->login($username, $password)) {
        header("Location: ../modules/dashboard/DashboardView.php");
        exit(); // Asegúrate de usar exit() después de header()
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping scan</title>
    <link rel="stylesheet" href="./css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./css/personalizado.css">
    
</head>
<body class="bg-dark">
<?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
<img src="./media/imagenes/fondo-inicio-seccion.jpg" alt=""
    class="banner-inicio-seccion"
    />
<main class="container">
   
    <div class="row fila-inicio-seccion">
        <div class="card imagen-usuario">
            <img src="./media/imagenes/imagen-usuario.png" alt="usuario"/>
        </div>    
    </div>
    <div class="row fila-inicio-seccion">
    <div class="col-12 col-sm-12 ">
        <div class="card card-body">
            <form  action="login.php" method="POST">
            <div class="card-title text-center"><h2>INICIO DE SECCION</h2></div>
            <div class="card-body">
                <div class="row"> <!-- inicio del row para modificar la apariencia de los inputs -->
                    <div class="col-sm-12 col-md-6">   <!-- inicio de la columna responsiva 1 -->
                <label for="usuario">Usuario: </label>
                <input name="usuario" id="usuario"
                type="text" placeholder="Ingrese su usuario"
                style="width:70%" required class="mb-3"
                />
</div><div class="col-sm-12 col-md-6"> <!-- inicio de la columna responsiva 2 y final de la 1 -->
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" 
                name="contrasena" placeholder="Ingrese su contraseña"
                style="width:70%" required
                />
                    </div><!-- final de la columna responsiva 2-->
                </div><!--Final del row para los inputs -->
            </div>
            <div class="card-footer text-center"><button class="btn btn-primary btn-inicio-seccion">Iniciar Seccion</button></div>
            </form>
        </div>
    </div>
    
    </div>
</main>
</body>
</html>
<!-- Logica de V.1.0.0 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../styles/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../styles/personalizado.css">
</head>
<body>
    
    
    <?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
    
</body>
</html> 
    -->