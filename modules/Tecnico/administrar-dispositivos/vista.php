<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/administrar-dispositivos/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorDispositivos($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/componentes/componentes.php';
$componentes = new Componentes();

//INICIAR SECCION
session_start();


//Mostrar los locales de manera filtrada
if(isset($_POST['locales_ip3'])){
    //  $dispositivos =$controlador->getDispositivosDeLocal($_POST['locales_ip3']);
    $_SESSION['local'] = $_POST['locales_ip3'];
}
if(isset($_POST['tipo_dispositivo_ip2'])){
    $_SESSION['tipo_dispositivo'] = $_POST['tipo_dispositivo_ip2'];
}

//Guardo el resultado de la consulta de mostrarDispositivos en $dispositivos
if($_SESSION['local'] !== null){ //si tiene el valor de un local mostrara una lista de solamente de ese local
    $dispositivos =$controlador->getDispositivosDeLocal($_SESSION['local']);
    $condicionDispositivos =$controlador->getDispositivosDeLocal($_SESSION['local']);
}else if($_SESSION['tipo_dispositivo'] !== null){
    $dispositivos =$controlador->getDispositivosDeTipo($_SESSION['tipo_dispositivo']);
    $condicionDispositivos =$controlador->getDispositivosDeTipo($_SESSION['tipo_dispositivo']);
}else if($_SESSION['local'] == null && $_SESSION['tipo_dispositivo'] == null && !isset($_POST['locales']) && !isset($_POST['tipo_dispositivos']) && !isset($_POST['orden'])){
$dispositivos = $controlador->mostrarDispositivos();
$condicionDispositivos = $controlador->mostrarDispositivos();
}

        //Filtro de dispositivos de acuerdo a varios parametros
        if(isset($_POST['locales']) || isset($_POST['tipo_dispositivos']) || isset($_POST['orden'])){

            if(isset($_POST['locales'])){
                $localesFiltro = $_POST['locales'];
            }else{
                $localesFiltro = false;
            }
        
            if(isset($_POST['tipo_dispositivos'])){
                $tiposDispositivosFiltro = $_POST['tipo_dispositivos'];
            }else{
                $tiposDispositivosFiltro = false;
            }
        
            if(isset($_POST['orden'])){
                $ordenFiltro = $_POST['orden'];
            }else{
                $ordenFiltro = false;
            }
            $dispositivos = $controlador->getDispositivosConFiltro($localesFiltro,$tiposDispositivosFiltro,$ordenFiltro);
            $condicionDispositivos = $controlador->getDispositivosConFiltro($localesFiltro,$tiposDispositivosFiltro,$ordenFiltro);
        }

        
$condicionDispositivosAuxiliar = $condicionDispositivos->fetch_assoc() !== null;
            
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





$filtrarDispositivos = null;
if (isset($_GET['filtrar_dispositivos'])) {
    $filtrarDispositivos = $_GET['filtrar_dispositivos'];
    $tiposDispositivos = $controlador->getTipoDispositivos();
    $locales= $controlador->getLocales();
    }




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
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php";
$_SESSION['notificacion']="";?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Tecnico/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de Dispositivos</h2></div>
    <div class="row"><!-- inicio del segundo row -->

        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->

            <!-- Inicio el cuadro de busqueda -->
    <div class="row">
            <div class="col-10 text-center" style="align-content: center;">
            
                <input type="text" placeholder="Buscar equipo" id="cuadro_busqueda"/>
            </div>
            <div class="col-2 p-1 text-center btn-filtrar">
            <a href="?filtrar_dispositivos=1" class="btn-warning">Filtrar</a>
            </div>
            <!-- Fin del cuadro de busqueda-->
    </div>
            <?php if($condicionDispositivosAuxiliar):?> <!-- inicio de mostrar dispositivos -->
    <table class="tabla-monitorear-dispositivos tabla-monitorear-dispositivos-tecnico">
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

            <div class="col-1 col-acciones col-acciones-tecnico"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_dispositivo=1">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Dispositivo"/>
            </a>
            <a href="?editar_dispositivo=" id="editar-dispositivo">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar dispositivo"/>
            </a>
            <a href="?eliminar_dispositivo=>" id="eliminar-dispositivo">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Añadir Dispositivo"/>    
            </a>
            <?php if($_SESSION['local'] !== null): ?>   
            <a href="?generar_documento=" id="generar-documento">
            <img src="/ping-scan/public/media/imagenes/documento.png" alt="Generar Documento"/>    
            </a>
            <?php endif; ?> 
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            <p id="os" style="z-index:-10;position:fixed;color:transparent"><?php echo $_SESSION['OS']?></p>    
            </div><!--final del segundo row -->
                <?php endif;?> <!-- fin de mostrar dispositivos -->

                
    <?php if($condicionDispositivosAuxiliar === false):?> <!-- inicio de mostrar dispositivos -->
        <div class="advertencia-dispositivos"><!-- inicio de advertencia-dispositivos -->
        <h1>Aun no hay dispositivos registrados dentro de este local</h1>
        </div><!-- fin de advertencia-dispositivos -->
    <?php endif; ?>


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
                <button type="submit" class="btn btn-primary ">Enviar</button>
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
                <input type="hidden" name="ip1" value="<?php echo htmlspecialchars($eliminarDispositivo['ip1']); ?>">
                <input type="hidden" name="ip2" value="<?php echo htmlspecialchars($eliminarDispositivo['tipo_dispositivo_ip2']); ?>">
                <input type="hidden" name="ip3" value="<?php echo htmlspecialchars($eliminarDispositivo['locales_ip3']); ?>">
                <input type="hidden" name="ip4" value="<?php echo htmlspecialchars($eliminarDispositivo['ip4']); ?>">
                <input type="hidden" name="nombre_equipo" value="<?php echo htmlspecialchars($eliminarDispositivo['nombre_equipo']); ?>">
                
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



            <?php if($filtrarDispositivos): ?>
        <div class="editar-fondo">  <!-- inicio de filtrar dispositivos -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="<?php echo $_SERVER['HTTP_REFERER']?>">X</a>
                <h2>Filtrar Dispositivos</h2>
                <form method="POST" action="./vista.php" class="formulario-filtro">
               <p>Locales de los dispositivos:</p>
                <?php while ($row = $locales->fetch_assoc()):
                    $ipDelLocal = $row['ip3']?>
                    <label class="input-filtro <?php if($ipDelLocal == $_SESSION['local']){ echo "input-filtro-seleccionado";} ?>">
                        <input type="checkbox" name="locales[]" value="<?php echo htmlspecialchars($ipDelLocal);?>" <?php if($ipDelLocal == $_SESSION['local']){echo "checked"; }?>/>
                        <?php echo htmlspecialchars($row['denominacion']);?>
                    </label>
                <?php endwhile; ?>
                </br></br>
                <p>Tipos de los dispositivos:</p>
                <?php while ($row = $tiposDispositivos->fetch_assoc()):
                    $ipDelTipo = $row['ip2']?>
                    <label class="input-filtro <?php if($ipDelTipo == $_SESSION['tipo_dispositivo']){ echo "input-filtro-seleccionado";} ?>">
                        <input type="checkbox" name="tipo_dispositivos[]" value="<?php echo htmlspecialchars($row['ip2']); ?>"  <?php if($ipDelTipo == $_SESSION['tipo_dispositivo']){ echo "checked";} ?>/>
                        <?php echo htmlspecialchars($row['equipo']);?>
                    </label>
                <?php endwhile; ?>
            </br></br>
            <p>Ordenar de manera:</p>
            <label class="input-filtro-radio" ><input type="radio" name="orden" value="ASC"/> Ascendente</label>
            <label class="input-filtro-radio" ><input type="radio" name="orden" value="DESC"/> Descendente</label>
                </br>
                <button type="submit" class="btn btn-primary mb-3 ">Enviar</button>
                </form>
    </div>
    </div> <!-- Fin de filtrar dispositivos -->
    <?php endif; ?> 


    </div><!-- Fin del div principal -->


    <?php if($condicionDispositivosAuxiliar):?> <!-- inicio de pingear dispositivos-->
    <script  src="script.js" type="module">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
    </script>
    <?php endif;?> <!-- fin de pingear dispositivos  --> 

    <script>
    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Tecnico/dashboard/DashboardView.php";})
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)

    
    //Logica para cambiar el color del boton de filtro al checkearlo
    for(let m=0;m<document.getElementsByClassName("input-filtro").length;m++){
        document.getElementsByClassName("input-filtro")[m].addEventListener("click", () =>{
            if(document.getElementsByClassName("input-filtro")[m].children[0].checked === true){
                document.getElementsByClassName("input-filtro")[m].className += " input-filtro-seleccionado";
            }else{
                document.getElementsByClassName("input-filtro")[m].className = "input-filtro";
            }
        })
    }
    //funcion para poder realizar la busqueda de un dispositivo 
    let cuadroBusqueda = document.getElementById("cuadro_busqueda");
    cuadroBusqueda.addEventListener("change", (e) =>{ //evento "change" en este caso cada vez que el elemento pierda el foco ejecutara la funcion
        for(i=0;i<max;i++){
            if(e.target.value == ""){
                document.getElementById(i).className="fila-datos"
            }
            if(document.getElementById(i).children[1].textContent.includes(e.target.value)){//si existe una coincidencia en la celda con el cuadro de busqueda
            //utilizamos string.search("palabra");
                const indicePrincipal = document.getElementById(i).children[1].textContent.search(e.target.value);
                const indiceFinal = indicePrincipal + (e.target.value.length);
                //console.log(document.getElementById(0).children[1].textContent.replace("DEF", 'XD'));
                //document.getElementById(i).children[1].textContent[indiceFinal]="xd";
                //document.getElementById(i).children[1].textContent[indicePrincipal]="xd"
                document.getElementById(i).children[1].innerHTML=document.getElementById(i).children[1].textContent.substring(0,indicePrincipal)+"<bold style='background-color:red;'>"+e.target.value+"</bold>"+document.getElementById(i).children[1].textContent.substring(indiceFinal);
                document.getElementById(i).className="fila-datos";


            }else{
                document.getElementById(i).className="busqueda-incorrecta";
            }

        }//fin del bucle
    
})//fin del evento



    </script>
</body>
</html>