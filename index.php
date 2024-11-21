<?php
include $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/controlador.php')) {
    require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/controlador.php';
} else {
    echo "Archivo controller.php no encontrado<br>";
}
$conexion = new conectar();
$conexion = $conexion->getConexion();

$controlador = new ControladorUsuarios($conexion);




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];

    // Validar campos (agrega más validaciones según tus necesidades)
    if (empty($usuario) || empty($nombre) ||empty($contrasena)) {
        echo "Por favor, complete todos los campos.";
    } else {
        $hashedContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (id_usuarios, usuario, nombre, rol, contrasena) VALUES (300100,?, ?, 'admin', ?)");
        $stmt->bind_param("sss", $usuario, $nombre, $hashedContrasena);
        //Ejecuta la funcion y genera una salida de acuerdo al resultado
        if ($stmt->execute()) {
            
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

    }
}

// Verificar si existe un administrador
$verificarAdmin = $conexion->query("SELECT * FROM usuarios WHERE rol = 'admin' LIMIT 1");



if ($verificarAdmin->num_rows > 0) {
    // Si ya existe un administrador, redirigir al login
    if (isset($usuario) && isset($contrasena) && $controlador->login($usuario, $contrasena)) {
        if($_SESSION['rol'] === 'admin'){
        header("Location: /ping-scan/modules/Administrador/dashboard/DashboardView.php");
        $_SESSION['notificacion'] = "Sesion Iniciada";
        exit(); // Asegúrate de usar exit() después de header()
        }
    }else{
        header("Location: /ping-scan/public/login.php");
        exit(); // Asegúrate de usar exit() después de header()
    }

}
$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping-Scan</title>
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
</head>
<body>
    <div class="container bg-dark text-light contenedor-registrar"><!-- div principal -->
    <h1 class="text-center p-4 text-primary" style="width: fit-content;margin: auto;border-bottom: thick solid;margin-bottom: 5%;">¡Bienvenido a Ping-Scan!</h1>
    <div class="row"><!--inicio del row principal -->
    <div class="col-12 col-lg-7"><!-- inicio del primer columna -->
    <div class="acerca-de"><!--inicio de info-ping-scan -->
        <h4 class="text-primary">¿Qué es Ping-Scan?</h4>
    <p>Ping-Scan es una plataforma web que te permite realizar un monitoreo de todos los equipos que registres.Esta aplicacion va fuertemente orientado al sector Retail los cuales cuentan con una gran cantidad de equipos los cuales comparten una necesidad importante "Estar dentro de la red".</p>
    <p>Gracias a Ping-Scan puedes ayudar a controlar esta necesidad dividiendo las tareas en una jerarquia de usuarios los cuales tendran acceso a los dispositivos que se encuentren en linea dentro de su VLAN.</p>
    </div><!-- fin de info-ping-scan -->
    </div> <!-- fin del primer columna -->
    <div class="col-12 col-lg-5" style="align-content: center;"> <!-- inicio del segundo columna-->
        <div class="formulario text-dark"><!-- inicio de formulario -->
    <h3>!Empieza ahora!</h3>
    <form method="POST" action="index.php">
    <label for="usuario">Usuario:</label>
                </br><input type="text" name="usuario" placeholder="Inserte el usuario" 
                id="usuario"class="mb-3 col-11 " required/>
                </br>
                <label for="nombre">Nombre:</label>
                </br><input type="text" id="nombre" name="nombre" placeholder="Inserte el nombre"
                class="mb-3 col-11 " required/>
                </br>
                <label>Contraseña:</label>
                </br><input type="password" id="contrasena" name="contrasena" placeholder="Introduzca una contraseña"
                class="mb-3 col-11" required/>
        </br>
        <div class="card-footer text-center">
        <button class="btn btn-success" type="submit">Registrarse</button>
</div>
    </form>
</div> <!-- fin de formulario -->
</div> <!-- fin del segundo columna -->
</div><!-- fin del row principal-->
    </div><!-- fin del div principal -->
</body>
</html>
