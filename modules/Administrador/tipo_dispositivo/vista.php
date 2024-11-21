<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/tipo_dispositivo/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorTipoDispositivo($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/componentes/componentes.php';
$componentes = new Componentes();


//Guardo el resultado de la consulta de mostrarLocales en $locales
$tipoDispositivo = $controlador->getTipoDispositivo();
$condicionTipo = $controlador->getTipoDispositivo();
$condicionTipoAuxiliar = $condicionTipo->fetch_assoc() !== null;

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
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Administrador/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de tipos de dispositivos</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
        <div class="row"><!--inicio de la fila para los iconos de locales -->



        <?php 
        $iterador=0;
        ?>


            <?php if($condicionTipoAuxiliar):?> <!-- inicio de mostrar tipos de dispositivos -->

            <?php while ($row = $tipoDispositivo->fetch_assoc()):?>
            <div class="col-12 col-lg-6 " title="Selecciona este tipo de dispositivo"><!-- div de cada card del local -->
                <div id="<?php echo $iterador?>" class="card-local card-dispositivo">
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
                <a class="btn btn-primary" href="?mostrar_detalles=<?php echo htmlspecialchars($row['id_tipo_dispositivo'])?>" title="Muestra mas detalles sobre el tipo de dispositivo">Más Detalles</a>
                </div>
            </div><!-- fin de div de cada card del local -->
                 <?php $iterador= $iterador+1;?>
                
            <?php endwhile; ?>

            </div> <!-- fin del row para los iconos de los locales -->
            </div><!-- fin de la columna para la tabla -->








            <div class="col-1 col-acciones"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_tipo=1" title="Añade un nuevo tipo de dispositivo">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Tipo"/>
            </a>
            <a href="?editar_tipo" id="editar-tipo" title="Modifica la informacion de un tipo de dispositivo">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar Tipo"/>
            </a>
            <a href="?eliminar_tipo" id="eliminar-tipo" title="Elimina el tipo de dispositivo">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Eliminar Tipo"/>    
            </a>    
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->

            <?php endif;?> <!-- fin de mostrar locales -->
            
            <?php if($condicionTipoAuxiliar === false):?> <!-- inicio de mostrar dispositivos -->
        <div class="advertencia-dispositivos" style="width: max-content;"><!-- inicio de advertencia-dispositivos -->
        <h1>Aun no hay ningun tipo de dispositivo registrado</h1>
        <p>¿Le gustaria agregar un nuevo tipo de dispositivo?</p>
        <a href="?añadir_tipo=1" class="btn btn-success">
        Añadir tipo de dispositivo
        </a>
        </div><!-- fin de advertencia-dispositivos -->
    <?php endif; ?>

        <?php if($añadirTipo): ?>
            <div class="editar-fondo">  <!-- inicio de añadir dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
                <h2>Añadir tipo de dispositivo</h2>
                <form method="POST" action="añadirTipoDispositivo.php">
                <label for="equipo">Tipo de dispositivo:</label>
                </br><input type="text" name="equipo" placeholder="Inserte el tipo de dispositivo" 
                id="equipo"class="mb-3 col-9 text-center" required
                <?php if(isset($_POST['equipo_advertencia'])){ echo "value=".$_POST['equipo_advertencia'];}?>  />
                </br>
                <label for="ip2">VLAN del dispositivo:</label>
            </br>
                <input type="number" max="255" min="0" name="ip2" id="ip2" 
                <?php if(isset($_POST['ip2_advertencia'])){ echo "value=".$_POST['ip2_advertencia'];}?> 
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
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
                <h2>Editar tipo de dispositivos</h2>
                <form method="POST" action="editarTipoDispositivo.php">
                <input type="hidden" name="id_tipo_dispositivo" value="<?php echo htmlspecialchars($editarTipo['id_tipo_dispositivo']);?>">
                <label for="equipo">Nombre del local:</label>
                </br><input type="text" name="equipo" placeholder="Inserte el tipo de dispositivo" 
                id="equipo"class="mb-3 col-9 text-center" required 
                value="<?php if(isset($_POST['equipo_advertencia'])){ echo $_POST['equipo_advertencia'];}else{echo htmlspecialchars($editarTipo['equipo']);}?>"/>
                </br>
                <label for="ip2">VLAN del tipo de dispositivo:</label>
            </br>
                <input type="number" max="255" min="0" name="ip2" id="ip2" 
                placeholder="X. Numero .X.X" required class="col-5 text-center"
                value="<?php if(isset($_POST['ip2_advertencia'])){ echo $_POST['ip2_advertencia'];}else{echo htmlspecialchars($editarTipo['ip2']);}?>"/>
            </br>
            
                <button type="submit" class="btn btn-primary mb-3">Editar tipo de dispositivo</button>
                </form>
    </div> <!-- fin de la ventana editar local -->
    </div> <!-- fin de editar-fondo --> 
                <?php endif;?> <!-- fin de editar local -->



            <?php if($eliminarTipo): ?><!-- inicio de eliminar dispositivo -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
                <h2 class="bg-danger">¿Estas seguro de eliminar estos dispositivos?</h2>
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
            
                <button type="submit" class="btn btn-danger mb-3">Eliminar dispositivos</button>
                </form>
    </div> <!-- fin de la ventana borrar local -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif; ?> <!-- fin de eliminar local -->

            <?php if($mostrarDetalles): ?>
            <div class="editar-fondo">  <!-- inicio de mostrar local -->
            <div class="card-mostrar-detalles">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
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
                    <img src="/ping-scan/public/media/imagenes/icono-iot.png" alt="Dispositivos"
                    style="width:100%;border-radius:10px;border-radius:10px;margin:2%;max-height: 200px;"/>
                </div><!--fin de columna imagen -->
                </div><!-- fin de card-mostrar-detalles-body -->

                <div class="card-mostrar-detalles-footer"><!-- inicio de card-mostrar-detalles-footer -->
                <a href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?editar_tipo=<?php echo htmlspecialchars($mostrarDetalles['id_tipo_dispositivo'])?>"
                class="btn btn-primary">Editar</a>
                <a href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?eliminar_tipo=<?php echo htmlspecialchars($mostrarDetalles['id_tipo_dispositivo'])?>"
                class="btn btn-danger">Eliminar</a>
            <form method="POST" action="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">    
            <input type="hidden" name="tipo_dispositivo_ip2" value="<?php echo htmlspecialchars($mostrarDetalles['ip2']);?>">
            <button class="btn btn-warning card-mostrar-detalles-mostrar-dispositivos" type="submit">Mostrar Dispositivos</button>
                <form>
            </div>
                </div><!-- fin de card-mostrar-detalles-footer -->
            </div> <!-- fin de la ventana mostrar local -->
            </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de mostrar detalles -->




            <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "tipo duplicado"): 
        $equipoAdvertencia = htmlspecialchars($_SESSION['error']['equipo']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
                        <h2>Tipo de equipo duplicado</h2> 
                        <p>Al parecer el tipo de equipo: <b style="color:red;"><?php echo $equipoAdvertencia ?></b> ya esta registrado.</p>
                        <p>Por favor, ingrese otro nombre para el tipo de equipo</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?añadir_tipo=1';} 
                       else if(isset($_SESSION['error']['origen']) && $_SESSION['error']['origen'] == "editar"){ echo '/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?editar_tipo='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip2_advertencia" value=  <?php echo $_SESSION['error']['ip2'];?>  />
                            <input type="hidden" name="equipo_advertencia" value=  <?php echo $_SESSION['error']['equipo'];?>  />
                           <input type="hidden" name="advertencia" value="tipo duplicado" />
                            <button type="submit" class="btn-primary">Cambiar nombre de tipo de equipo</button>
                        </form>

                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
              <?php  endif;?> <!-- fin de denominacion duplicada -->  



















            
            <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "ip duplicada"): 
        $ipAdvertencia = htmlspecialchars($_SESSION['error']['ip2']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">X</a>
                        <h2>IP duplicada</h2> 
                        <p>Al parecer la IP: <b style="color:red;"><?php echo $ipAdvertencia ?></b> ya esta registrada bajo otro tipo de equipo.</p>
                        <p>Por favor, ingrese otra IP para el tipo de equipo</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?añadir_tipo=1';} 
                       else if(isset($_SESSION['error']['origen']) && $_SESSION['error']['origen'] == "editar"){ echo '/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?editar_tipo='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip2_advertencia" value=  <?php echo $_SESSION['error']['ip2'];?>  />
                            <input type="hidden" name="equipo_advertencia" value=  <?php echo $_SESSION['error']['equipo'];?>  />
                            <input type="hidden" name="advertencia" value="ip duplicada" />
                            <button type="submit" class="btn-primary">Cambiar IP</button>
                        </form>

                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
              <?php  endif;?> <!-- fin de ip duplicada -->  
            














                <?php $_SESSION['error']=null; ?>
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
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Administrador/dashboard/DashboardView.php";})
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)

    
//como enfocar en el campo IP cuando hay un error de ip duplicada
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="tipo duplicado"):?>
    if(document.getElementById("equipo")){
        document.getElementById("equipo").focus();
        document.getElementById("equipo").value="";
    }
<?php endif;?>

//como enfocar en el campo denominacion cuando hay un error de denominacion duplicada
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="ip duplicada"):?>
    if(document.getElementById("ip2")){
        document.getElementById("ip2").focus();
        document.getElementById("ip2").value="";
    }
<?php endif;?>
    //codigo    document.getElementsByClassName("col-acciones")[0].children[n]
    </script>
</body>
</html>