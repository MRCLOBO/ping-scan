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
$_SESSION['OS']= php_uname("s");
if(!isset($_SESSION['notificacion'])){
    $_SESSION['notificacion']="";
}

// Crear una instancia de la clase Conectar y obtener la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear una instancia del controlador de usuario
$controlador = new ControladorUsuarios($conn);

$verificarUsuarios = $conn->query("SELECT * FROM usuarios");

if ($verificarUsuarios->num_rows == 0) {
        header("Location: /ping-scan/index.php");
        exit(); // Asegúrate de usar exit() después de header()
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];
    $patron_texto = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜàèìòùÀÈÌÒÙ0123456789\s]+$/";

    if( preg_match($patron_texto, $_POST['usuario']) ){
        if ($controlador->login($username, $password)) {
            if($_SESSION['rol'] === 'admin'){
            header("Location: /ping-scan/modules/Administrador/dashboard/DashboardView.php");
            $_SESSION['notificacion'] = "Sesion Iniciada";
            exit(); // Asegúrate de usar exit() después de header()
            }else if ($_SESSION['rol'] === 'tecnico'){
                header("Location: /ping-scan/modules/Tecnico/dashboard/DashboardView.php");
                $_SESSION['notificacion'] = "Sesion Iniciada";
                exit();
            }else if($_SESSION['rol'] === 'user'){
    
                header("Location: /ping-scan/modules/Usuario/administrar-dispositivos/vista.php");
                $_SESSION['notificacion'] = "Sesion Iniciada";
                exit();
            }
        
        } else {
            $_SESSION['notificacion'] = "Nombre de usuario o contraseña incorrectos.";
        }
    }else{
        $_SESSION['notificacion'] = "Ingrese solo letras para el nombre";
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

<img src="./media/imagenes/fondo-inicio-seccion.jpg" alt=""
    class="banner-inicio-seccion"
    />
    
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php"?>
<main class="container">
   
    <div class="row fila-inicio-seccion">
        <div class="card imagen-usuario">
            <img src="/ping-scan/public/media/imagenes/imagen-usuario.png" alt="usuario"/>
        </div>    
    </div>
    <div class="row fila-inicio-seccion">
    <div class="col-12 col-sm-12 ">
        <div class="card card-body m-3">
            <form  action="login.php" method="POST">
            <div class="card-title text-center"><h2>INICIO DE SESION</h2></div>
            <div class="card-body">
                <div class="row"> <!-- inicio del row para modificar la apariencia de los inputs -->
                <label for="usuario" class="col-12 col-lg-1 text-center">Usuario: </label>
                <input name="usuario" id="usuario" title="Ingrese su usuario"
                type="text" placeholder="Ingrese su usuario"
            required class="col-12 col-lg-4 mb-3 p-1"
                />
                <div class="col-0 col-lg-1"></div>

                <label for="contrasena" class="col-12 col-lg-2 text-center">Contraseña:</label>
                <input type="password" id="contrasena" title="Ingrese su contraseña"
                name="contrasena" placeholder="Ingrese su contraseña"
                 required class="col-12 col-lg-4 mb-3 p-1"
                />

                </div><!--Final del row para los inputs -->
            </div>
            <div class="card-footer text-center"><button class="btn btn-primary btn-inicio-seccion" title="Iniciar Sesion">Iniciar Sesion</button></div>
            </form>
        </div>
    </div>
    
    </div>
</main>
<script>
    //Ejemplo despues de perder el foco 
    //document.getElementById("contrasena").addEventListener("blur", function() {document.getElementById("contrasena").style.backgroundColor="blue"})

    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)
</script>
</body>
</html>
