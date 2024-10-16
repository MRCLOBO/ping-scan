<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php'; 
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/controlador.php')) {
    require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/controlador.php';
} else {
    echo "Archivo controller.php no encontrado<br>";
}



session_start();
$_SESSION['OS']= php_uname("a");
// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controlador = new ControladorUsuarios($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];
    
    
    if ($controlador->login($username, $password)) {
        if($_SESSION['rol'] === 'admin'){
        header("Location: /ping-scan/modules/Administrador/dashboard/DashboardView.php");
        exit(); // Asegúrate de usar exit() después de header()
        }else if ($_SESSION['rol'] === 'tecnico'){
            header("Location: /ping-scan/modules/Tecnico/dashboard/DashboardView.php");
            exit();
        }else if($_SESSION['rol'] === 'user'){

            header("Location: /ping-scan/modules/Usuario/administrar-dispositivos/vista.php");
            exit();
        }
    
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
    <title>Inicio de Seccion</title>
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
    
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
            <img src="/ping-scan/public/media/imagenes/imagen-usuario.png" alt="usuario"/>
        </div>    
    </div>
    <div class="row fila-inicio-seccion">
    <div class="col-12 col-sm-12 ">
        <div class="card card-body m-5">
            <form  action="login.php" method="POST">
            <div class="card-title text-center"><h2>INICIO DE SECCION</h2></div>
            <div class="card-body">
                <div class="row"> <!-- inicio del row para modificar la apariencia de los inputs -->
                <label for="usuario" class="col-12 col-lg-1 text-center">Usuario: </label>
                <input name="usuario" id="usuario"
                type="text" placeholder="Ingrese su usuario"
            required class="col-12 col-lg-4 mb-3"
                />
                <div class="col-0 col-lg-1"></div>

                <label for="contrasena" class="col-12 col-lg-2 text-center">Contraseña:</label>
                <input type="password" id="contrasena" 
                name="contrasena" placeholder="Ingrese su contraseña"
                 required class="col-12 col-lg-4 mb-3"
                />

                </div><!--Final del row para los inputs -->
            </div>
            <div class="card-footer text-center"><button class="btn btn-primary btn-inicio-seccion">Iniciar Seccion</button></div>
            </form>
        </div>
    </div>
    
    </div>
</main>
<script>
    console.log("<?php $_SESSION['OS'] ;?>")
</script>
</body>
</html>
