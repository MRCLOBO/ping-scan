<?php require 'DashboardController.php';
$_SESSION['local'] = null;
$_SESSION['tipo_dispositivo']= null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
</head>

<body class="bg-dark text-light">
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php";
$_SESSION['notificacion']="";?>

    <div class="container col-md-12" style="height:100vh;"> <!--div principal-->
    <?php require '../componentes/navbar.php';?> <!-- llamada al navbar -->
    <h2 class="text-center m-2">PANEL PRINCIPAL</h2>
    <div class="row"><!-- row principal -->
    <div class="col-md-3 col-12 p-2">
            <div class="card-opcion">
            <form method="POST" action="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">
                <button type="submit" class="card-opcion-boton">
            <img src="/ping-scan/public/media/imagenes/icono-usuarios.png" alt="">
            <h3 class="card-opcion-h3">Administrar Usuarios</h3>    
            </button>
            </form>
        </div>
        </div>
        <div class="col-md-3 col-12 p-2">
            <div class="card-opcion">
            <form method="POST" action="/ping-scan/modules/Administrador/gestionar_locales/vista.php"> <!-- definir donde va -->
                <button type="submit" class="card-opcion-boton">
            <img src="/ping-scan/public/media/imagenes/icono-local.png" alt="">
            <h3 class="card-opcion-h3">Gestionar Locales</h3>    
            </button>
            </form>
        </div>
        </div>

        <div class="col-md-3 col-12 p-2">
            <div class="card-opcion">
            <form method="POST" action="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php"> <!-- definir donde va -->
                <button type="submit" class="card-opcion-boton">
            <img src="/ping-scan/public/media/imagenes/icono-pc.png" alt="">
            <h3 class="card-opcion-h3">Administrar Tipos de Dispositivos</h3>    
            </button>
            </form>
        </div>
        </div>

        <div class="col-md-3 col-12 p-2  mb-3">
            <div class="card-opcion">
            <form method="POST" action="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">
                <button type="submit" class="card-opcion-boton">
            <img src="/ping-scan/public/media/imagenes/icono-red.png" alt="">
            <h3 class="card-opcion-h3">Gestionar Dispositivos</h3>    
            </button>
            </form>
        </div>
        </div>

    </div>
    
</div> <!-- cierre de div principal-->
<script>
        //Boton atras
        document.getElementById("boton-atras").addEventListener("click",() =>{ window.location.href = "/ping-scan/public/login.php"})
        setInterval(()=>{
        document.getElementById("notificacion").className="notificacion-desaparecer"
        },3000)
    //Vaciar la cadena de notificacion cuando se entre a una de las opciones
    for(let i=0; i < document.querySelectorAll(".card-opcion-boton").length ; i++){
    document.querySelectorAll(".card-opcion-boton")[i].addEventListener("click",() => {<?php $_SESSION['notificacion']= "" ?>})
    }
</script>
</body>
</html>