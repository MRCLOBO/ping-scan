<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/tipo_dispositivo/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorTipoDispositivo($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/componentes/componentes.php';
$componentes = new Componentes();


//Guardo el resultado de la consulta de mostrarLocales en $locales
$tipoDispositivo = $controlador->getTipoDispositivo();

//funcion para añadir local
$añadirTipo = null;
if (isset($_GET['añadir_tipo'])) {
    $añadirTipo = true;
}
//funcion para editar un local seleccionado
$editarTipo = null;
if (isset($_GET['editar_tipo'])) {
    $editarTipo = $controlador->getEditarTipo($_GET['editar_tipo']);
}
//funcion para eliminar local seleccionado
$eliminarTipo = null;
if (isset($_GET['eliminar_tipo'])) {
    $eliminarTipo = $controlador->getEditarTipo($_GET['eliminar_tipo']);
}
$mostrarDetalles = null;
if (isset($_GET['mostrar_detalles'])) {
    $mostrarDetalles = $controlador->getEditarTipo($_GET['mostrar_detalles']);
}
//INICIAR SECCION
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /ping-scan/public/login.php');
    exit();
}
else{
    // Enviar los datos necesarios a la vista
    $user = json_decode(json_encode($_SESSION['usuario']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion de Tipos de Dispositivos</title>
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
</head>
<body class="bg-dark text-light">
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php";
$_SESSION['notificacion']="";?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Tecnico/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de tipos de dispositivos</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
        <div class="row"><!--inicio de la fila para los iconos de locales -->



        <?php 
        $iterador=0;
        ?>




            <?php while ($row = $tipoDispositivo->fetch_assoc()):?>
            <div class="col-12 col-lg-6 "><!-- div de cada card del local -->
                <div id="<?php echo $iterador?>" class="card-local card-local-tecnico card-dispositivo">
                <h4 id="<?php echo htmlspecialchars($row['id_tipo_dispositivo']);?>"><?php echo htmlspecialchars($row['equipo']);?></h4>
                <img src="/ping-scan/public/media/imagenes/icono-iot.png" alt="Dispositivos"/>
                <div class="card-local-info"><!-- card-local-info-->
                <p>Dispositivos registrados:
                    <?php 
                    
                     $dispositivosConTipo = $controlador->getDispositivosConTipo($row['ip2']);
                     $mostrarDispositivosDeTipo = $dispositivosConTipo->fetch_assoc();
                     echo htmlspecialchars($mostrarDispositivosDeTipo['count(*)']);
                    
                    ?>
                </p>
                <p>VLAN del equipo: <?php echo htmlspecialchars($row['ip2']);?></p>
                </div><!-- fin de card-local-info-->
                <a class="btn btn-success" href="?mostrar_detalles=<?php echo htmlspecialchars($row['id_tipo_dispositivo'])?>">Más Detalles</a>
                </div>
            </div><!-- fin de div de cada card del local -->
                 <?php $iterador= $iterador+1;?>
                
            <?php endwhile; ?>

            </div> <!-- fin del row para los iconos de los locales -->
            </div><!-- fin de la columna para la tabla -->








            <div class="col-1 col-acciones col-acciones-tecnico"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_tipo=1">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Tipo"/>
            </a>
            <a href="?editar_tipo" id="editar-tipo">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar Tipo"/>
            </a>
            <a href="?eliminar_tipo" id="eliminar-tipo">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Eliminar Tipo"/>    
            </a>    
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->



        <?php if($añadirTipo): ?>
            <div class="editar-fondo">  <!-- inicio de añadir dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/tipo_dispositivo/vista.php">X</a>
                <h2>Añadir tipo de dispositivo</h2>
                <form method="POST" action="añadirTipoDispositivo.php">
                <label for="equipo">Tipo de dispositivo:</label>
                </br><input type="text" name="equipo" placeholder="Inserte el tipo de dispositivo" 
                id="equipo"class="mb-3 col-9 text-center" required/>
                </br>
                <label for="ip2">VLAN del dispositivo:</label>
            </br>
                <input type="number" max="255" min="0" name="ip2" id="ip2" 
                placeholder="X. Numero .X.X" required class="col-5 text-center"/>
            </br>
            
                <button type="submit" class="btn btn-primary mb-3">Añadir tipo de dispositivo</button>
                </form>
    </div> <!-- fin de la ventana añadir local -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de añadir Local -->
    

            <?php if($editarTipo): ?>
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/tipo_dispositivo/vista.php">X</a>
                <h2>Editar tipo de dispositivos</h2>
                <form method="POST" action="editarTipoDispositivo.php">
                <input type="hidden" name="id_tipo_dispositivo" value="<?php echo htmlspecialchars($editarTipo['id_tipo_dispositivo']);?>">
                <label for="equipo">Nombre del local:</label>
                </br><input type="text" name="equipo" placeholder="Inserte el tipo de dispositivo" 
                id="equipo"class="mb-3 col-9 text-center" required 
                value="<?php echo htmlspecialchars($editarTipo['equipo'])?>"/>
                </br>
                <label for="ip2">VLAN del tipo de dispositivo:</label>
            </br>
                <input type="number" max="255" min="0" name="ip2" id="ip2" 
                placeholder="X. Numero .X.X" required class="col-5 text-center"
                value="<?php echo htmlspecialchars($editarTipo['ip2'])?>"/>
            </br>
            
                <button type="submit" class="btn btn-primary mb-3">Editar tipo de dispositivo</button>
                </form>
    </div> <!-- fin de la ventana editar local -->
    </div> <!-- fin de editar-fondo --> 
                <?php endif;?> <!-- fin de editar local -->



            <?php if($eliminarTipo): ?><!-- inicio de eliminar dispositivo -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                <h2>¿Estas seguro de eliminar estos dispositivos?</h2>
                <form method="POST" action="eliminarTipoDispositivo.php">
                <input type="hidden" name="id_tipo_dispositivo" value="<?php echo htmlspecialchars($eliminarTipo['id_tipo_dispositivo']);?>">
                <input type="hidden" name="ip2" value="<?php echo htmlspecialchars($eliminarTipo['ip2']); ?>">
                <input type="hidden" name="equipo" value="<?php echo htmlspecialchars($eliminarTipo['equipo']); ?>">
                <label for="equipo">Tipo de dispositivo:</label>
                </br><input type="text" name="equipo" placeholder="Inserte el tipo de dispositivo aqui" 
                id="equipo"class="mb-3 col-9 text-center" required disabled
                value="<?php echo htmlspecialchars($eliminarTipo['equipo'])?>"/>
                </br>
                <label for="ip2">VLAN del tipo de dispositivo:</label>
            </br>
                <input type="number" max="255" min="0" name="ip2" id="ip2" 
                placeholder="X. Numero .X.X" required class="col-5 text-center"
                value="<?php echo htmlspecialchars($eliminarTipo['ip2'])?>" disabled/>
            </br>
            
                <button type="submit" class="btn btn-primary mb-3">Eliminar dispositivos</button>
                </form>
    </div> <!-- fin de la ventana borrar local -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif; ?> <!-- fin de eliminar local -->

            <?php if($mostrarDetalles): ?>
            <div class="editar-fondo">  <!-- inicio de mostrar local -->
            <div class="card-mostrar-detalles">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/tipo_dispositivo/vista.php">X</a>
                <h2><?php echo htmlspecialchars($mostrarDetalles['equipo'])?></h2>
                <div class="row"> <!-- inicio de card-mostrar-detalles-body -->
                    
                <div class="col-lg-9"><!-- columna de detalles-->
                <p>VLAN: <bold><?php echo htmlspecialchars($mostrarDetalles['ip2'])?></bold></p>
                <p>Dispositivos registrados: <?php 
                     $dispositivosConTipo = $controlador->getDispositivosConTipo($mostrarDetalles['ip2']);
                     $mostrarDispositivosDeTipo = $dispositivosConTipo->fetch_assoc();
                     echo htmlspecialchars($mostrarDispositivosDeTipo['count(*)']);
                    ?></p>
                </div><!--fin de columna de detalles -->

                <div class="col-lg-3 align-content-center"><!-- columna imagen -->
                    <img src="/ping-scan/public/media/imagenes/icono-iot.png" alt="Local"
                    style="width:100%;border-radius:10px;border:thin solid;border-radius:10px;margin:2%;max-height: 200px;"/>
                </div><!--fin de columna imagen -->
                </div><!-- fin de card-mostrar-detalles-body -->

                <div class="card-mostrar-detalles-footer"><!-- inicio de card-mostrar-detalles-footer -->
                <a href="/ping-scan/modules/Tecnico/tipo_dispositivo/vista.php?editar_tipo=<?php echo htmlspecialchars($mostrarDetalles['id_tipo_dispositivo'])?>"
                class="btn btn-primary">Editar</a>
                <a href="/ping-scan/modules/Tecnico/tipo_dispositivo/vista.php?eliminar_tipo=<?php echo htmlspecialchars($mostrarDetalles['id_tipo_dispositivo'])?>"
                class="btn btn-danger">Eliminar</a>
            <form method="POST" action="/ping-scan/modules/Tecnico/administrar-dispositivos/vista.php">    
            <input type="hidden" name="tipo_dispositivo_ip2" value="<?php echo htmlspecialchars($mostrarDetalles['ip2']);?>">
            <button class="btn btn-warning card-mostrar-detalles-mostrar-dispositivos" type="submit">Mostrar Dispositivos</button>
                <form>
            </div>
                </div><!-- fin de card-mostrar-detalles-footer -->
            </div> <!-- fin de la ventana mostrar local -->
            </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de mostrar detalles -->
    
    </div><!-- Fin del div principal -->


   <script>
    const tamañoTabla=<?php echo $iterador ?>;
    let n;
    for(n=0;n<tamañoTabla;n++){
        const valorActual=n;
        document.getElementById(valorActual).addEventListener("click",() => {
            //eliminar el foco actual
            if(document.getElementsByClassName("card-dispositivo-seleccionado")[0]){
            document.getElementsByClassName("card-dispositivo-seleccionado")[0].className="card-local card-dispositivo";
            }
            //cambiar el foco actual por el elemento seleccionado
            document.getElementById(valorActual).className="card-dispositivo-seleccionado";
            //cambiar la ruta del boton para editar
            document.getElementById("editar-tipo").href="?editar_tipo="+document.getElementById(valorActual).children[0].id;
            //cambiar la ruta del boton para eliminar
            document.getElementById("eliminar-tipo").href="?eliminar_tipo="+document.getElementById(valorActual).children[0].id;
        });
    }
    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Tecnico/dashboard/DashboardView.php";})
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)

    //codigo    document.getElementsByClassName("col-acciones")[0].children[n]
    </script>
</body>
</html>