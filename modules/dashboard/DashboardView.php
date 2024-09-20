<?php require 'DashboardController.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../public/css/personalizado.css">
</head>

<body>
    <div class="container col-md-12 bg-dark text-light" style="height:100vh;"> <!--div principal-->
    <?php require '../componentes/navbar.php';?> <!-- llamada al navbar -->
    <h2 class="text-center m-2">PANEL PRINCIPAL</h2>
    <div class="row"><!-- row principal -->
    <div class="col-4 p-2">
            <div class="card-opcion">
            <form method="POST" action="../seguridad_autenticacion/view.php">
                <button type="submit" class="card-opcion-boton">
            <img src="../../public/media/imagenes/icono-usuarios.png" alt="">
            <h3 class="card-opcion-h3">Administrar Usuarios</h3>    
            </button>
            </form>
        </div>
        </div>
        <div class="col-4 p-2">
            <div class="card-opcion">
            <form method="POST" action=""> <!-- definir donde va -->
                <button type="submit" class="card-opcion-boton">
            <img src="../../public/media/imagenes/icono-local.png" alt="">
            <h3 class="card-opcion-h3">Gestionar Locales</h3>    
            </button>
            </form>
        </div>
        </div>
        <div class="col-4 p-2">
            <div class="card-opcion">
            <form method="POST" action="">
                <button type="submit" class="card-opcion-boton">
            <img src="../../public/media/imagenes/icono-red.png" alt="">
            <h3 class="card-opcion-h3">Gestionar Dispositivos</h3>    
            </button>
            </form>
        </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    <!-- V 1.2.0       
    <? //php if ($user->rol === 'admin'):?>
                <h3>Opciones de Administrador</h3>
                <ul>
                <li><a href="../seguridad_autenticacion/view.php">Gestión de Usuarios</a></li>
                </ul>
            <?php  ?>
    <?php // if ($user->rol === 'admin' || $user->rol === 'tec' ): ?>
            <ul>
            <li><a href="gestion_dispositivos.php">Gestión de Dispositivos</a></li>
            <?php ?>

            <li><a href="informes.php">Informes Históricos</a></li>
        </ul>

    -->


    
</div> <!-- cierre de div principal-->
</body>

</html>