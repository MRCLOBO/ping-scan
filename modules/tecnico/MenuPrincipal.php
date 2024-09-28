<?php 
require 'Controlador.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Tecnico</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'];?>/ping-scan/public/css/personalizado.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'];?>/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
</head>

<body>
    <div class="container col-md-12 bg-dark text-light m-0" style="height:100vh;margin:0;"> <!--div principal-->
    <?php require '../componentes/navbar.php';?> <!-- llamada al navbar -->
    <h2 class="text-center m-2">MENU PRINCIPAL (TECNICO)</h2>
    <div class="row"><!-- row principal -->
        <div class="col-3"></div>
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
            <form method="POST" action="../administrar-dispositivos/vista.php">
                <button type="submit" class="card-opcion-boton">
            <img src="../../public/media/imagenes/icono-red.png" alt="">
            <h3 class="card-opcion-h3">Gestionar Dispositivos</h3>    
            </button>
            </form>
        </div>
        </div>
    </div>
    
</div> <!-- cierre de div principal-->
</body>

</html>