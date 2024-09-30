<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/administrar-dispositivos/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorDispositivos($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/componentes/componentes.php';
$componentes = new Componentes();


//Guardo el resultado de la consulta de mostrarDispositivos en $dispositivos
$dispositivos = $controlador->mostrarDispositivos();

//funcion para añadir dispositivo
$añadirDispositivo = null;
if (isset($_GET['añadir_dispositivo'])) {
    $añadirDispositivo = true;
}
//funcion para editar un dispositivo seleccionado
$editarDispositivo = null;
if (isset($_GET['editar_dispositivo'])) {
    $editarDispositivo = $controlador->getEditarDispositivo($_GET['editar_dispositivo']);
}
//funcion para eliminar un dispositivo seleccionado
$eliminarDispositivo = null;
if (isset($_GET['eliminar_dispositivo'])) {
    $eliminarDispositivo = $controlador->getEditarDispositivo($_GET['eliminar_dispositivo']);
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
    <title>Administracion de Dispositivos</title>
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
</head>
<body class="bg-dark text-light">
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de Dispositivos</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
    <table class="tabla-monitorear-dispositivos">
        <thead >
            <tr>
                <th>IP</th>
                <th>Nombre del equipo</th>
                <th>Local del dispositivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <?php 
        $iterador=0;
        ?>
        <tbody >
            <?php while ($row = $dispositivos->fetch_assoc()):
                $ip1= htmlspecialchars($row['ip1']);
                $ip2=htmlspecialchars($row['tipo_dispositivo_ip2']);
                $ip3=htmlspecialchars($row['locales_ip3']);
                $ip4=htmlspecialchars($row['ip4']);
                $ip_actual= $ip1.'.'.$ip2.'.'.$ip3.'.'.$ip4;
                
                $pedirLocal = $controlador->localDeDispositivo($ip3);
                $local= htmlspecialchars($pedirLocal['denominacion']);

                ?>
                
                <tr id=<?php echo $iterador?> class="fila-datos">
                    <td id=<?php echo htmlspecialchars($row['id_dispositivos']);?>><?php echo $ip_actual?></td>
                    <td><?php echo htmlspecialchars($row['nombre_equipo']);?></td>
                    <td><?php echo $local ?></td>
                    <td class="esperando-conexion"> Esperando la conexion... </td>
                </tr>
                
                 <?php $iterador= $iterador+1;?>
                
            <?php endwhile; ?>
            </tbody>
    </table>
            </div><!-- fin de la columna para la tabla -->

            <div class="col-1 col-acciones"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_dispositivo=1">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Dispositivo"/>
            </a>
            <a href="?editar_dispositivo=" id="editar-dispositivo">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar dispositivo"/>
            </a>
            <a href="?eliminar_dispositivo=>" id="eliminar-dispositivo">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Añadir Dispositivo"/>    
            </a>    
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->



        <?php if($añadirDispositivo): ?>
            <?php $componentes->ventanaDispositivo();?>
            <?php endif;?> <!-- fin de añadir dispositivo -->
    

            <?php if($editarDispositivo): ?>
        <div class="editar-fondo">  <!-- inicio de editar dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="<?php echo $_SERVER['HTTP_REFERER']?>">X</a>
                <h2>Editar dispositivo</h2>
                <form method="POST" action="editarDispositivo.php">
                <input type="hidden" name="id_dispositivos" value="<?php echo htmlspecialchars($editarDispositivo['id_dispositivos']); ?>">
                <label for="ip1">Direccion IP del dispositivo:</label>
                <div class="solicitar-ip"><!-- poner la ip completa -->
                <input  type="number" max="255" min="0" id="ip1" name="ip1" required
                value="<?php echo $editarDispositivo['ip1']?>"/>
                <label for="ip2">.</label>
                <input type="number" max="255" min="0" id="ip2" name="ip2" required
                value="<?php echo htmlspecialchars($editarDispositivo['tipo_dispositivo_ip2'])?>"/>
                <label for="ip3">.</label>
                <input type="number" max="255" min="0" id="ip3" name="ip3" required
                value="<?php echo htmlspecialchars($editarDispositivo['locales_ip3'])?>"/>
                <label for="ip4">.</label>
                <input type="number" max="255" min="0" id="ip4" name="ip4" required
                value="<?php echo htmlspecialchars($editarDispositivo['ip4'])?>"/>
                </div> <!--fin de poner la ip completa -->
    </br>
                <label for="nombre_equipo">Nombre del dispositivo</label>
                </br>
                <input type="text" id="nombre_equipo" name="nombre_equipo"
                value="<?php echo htmlspecialchars($editarDispositivo['nombre_equipo'])?>"/>
    </br>
                <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
    </div>
    </div> 
            <?php endif;?> <!-- fin de editar dispositivo -->

            <?php if($eliminarDispositivo): ?><!-- inicio de eliminar dispositivo -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="<?php echo $_SERVER['HTTP_REFERER']?>">X</a>
                <h3 class="p-3 bg-danger">Eliminar dispositivo</h3>
                <form method="POST" action="eliminarDispositivo.php">
                <input type="hidden" name="id_dispositivos" value="<?php echo htmlspecialchars($eliminarDispositivo['id_dispositivos']); ?>">
                <label for="ip1">Direccion IP del dispositivo:</label>
                <div class="solicitar-ip">
                <input  type="number" max="255" min="0" id="ip1" name="ip1" required disabled
                value="<?php echo $eliminarDispositivo['ip1']?>"/>
                <label for="ip2">.</label>
                <input type="number" max="255" min="0" id="ip2" name="ip2" required disabled
                value="<?php echo htmlspecialchars($eliminarDispositivo['tipo_dispositivo_ip2'])?>"/>
                <label for="ip3">.</label>
                <input type="number" max="255" min="0" id="ip3" name="ip3" required disabled
                value="<?php echo htmlspecialchars($eliminarDispositivo['locales_ip3'])?>"/>
                <label for="ip4">.</label>
                <input type="number" max="255" min="0" id="ip4" name="ip4" required disabled
                value="<?php echo htmlspecialchars($eliminarDispositivo['ip4'])?>"/>
                </div>
    </br>
                <label for="nombre_equipo">Nombre del dispositivo</label>
                </br>
                <input type="text" id="nombre_equipo" name="nombre_equipo" disabled
                value="<?php echo htmlspecialchars($eliminarDispositivo['nombre_equipo'])?>"/>
    </br>
    <p>¿Estas Seguro de que deseas eliminar el dispositivo?</p>
                <button type="submit" class="btn btn-danger">Enviar</button>
                </form>
    </div>
    </div> 
            <?php endif; ?> <!-- fin de eliminar dispositivo -->
            
    </div><!-- Fin del div principal -->


    <script  src="script.js" type="module">
    </script>
    <script>
    const tamañoTabla=<?php echo $iterador ?>;
    let n;
    let max = Number(document.getElementById("auxiliar-iterador").textContent) //Cantidad de filas de la tabla
    for(n=0;n<max;n++){
        const valorActual=n;
        document.getElementById(valorActual).addEventListener("click",() => {
            //eliminar el foco actual
            if(document.getElementsByClassName("fila-seleccionada")[0]){
            document.getElementsByClassName("fila-seleccionada")[0].className="";
            }
            //cambiar el foco actual por el elemento seleccionado
            document.getElementById(valorActual).className="fila-seleccionada";
            //cambiar la ruta del boton para editar
            document.getElementById("editar-dispositivo").href="?editar_dispositivo="+document.getElementById(valorActual).children[0].id;
            //cambiar la ruta del boton para eliminar
            document.getElementById("eliminar-dispositivo").href="?eliminar_dispositivo="+document.getElementById(valorActual).children[0].id;
        });
    }
  //console.log("<?php// echo $_SERVER['DOCUMENT_ROOT']?>") respuesta: C:/laragon/www
    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "../dashboard/DashboardView.php";})
    </script>
</body>
</html>