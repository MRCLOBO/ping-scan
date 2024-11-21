<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/gestionar_locales/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorLocales($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Tecnico/componentes/componentes.php';
$componentes = new Componentes();


//Guardo el resultado de la consulta de mostrarLocales en $locales
$locales = $controlador->getLocales();
$condicionLocales = $controlador->getLocales();
$condicionLocalesAuxiliar = $condicionLocales->fetch_assoc() !== null;


//funcion para añadir local
$añadirLocal = null;
if (isset($_GET['añadir_local'])) {
    $añadirLocal = true;
}
//funcion para editar un local seleccionado
$editarLocal = null;
if (isset($_GET['editar_local'])) {
    $editarLocal = $controlador->getEditarLocal($_GET['editar_local']);
}
//funcion para eliminar local seleccionado
$eliminarLocal = null;
if (isset($_GET['eliminar_local'])) {
    $eliminarLocal = $controlador->getEditarLocal($_GET['eliminar_local']);
}
$mostrarDetalles = null;
if (isset($_GET['mostrar_detalles'])) {
    $mostrarDetalles = $controlador->getEditarLocal($_GET['mostrar_detalles']);
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
    <title>Gestionar Locales</title>
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
</head>
<body class="text-light">
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php";
$_SESSION['notificacion']=""; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Tecnico/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Gestion de Locales</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
        <div class="row"><!--inicio de la fila para los iconos de locales -->



        <?php 
        $iterador=0;
        ?>



<?php if($condicionLocalesAuxiliar):?> <!-- inicio de mostrar locales -->
            <?php while ($row = $locales->fetch_assoc()):?>
            <div class="col-6 col-lg-4 "><!-- div de cada card del local -->
                <div id="<?php echo $iterador?>" class="card-local card-local-tecnico">
                <h4 id="<?php echo htmlspecialchars($row['id_locales']);?>"><?php echo htmlspecialchars($row['denominacion']);?></h4>
                <img src="/ping-scan/public/media/imagenes/super6.png" alt="Local"/>
                <div class="card-local-info"><!-- card-local-info-->
                <p>Dispositivos registrados:
                    <?php 
                     $dispositivosDeLocal = $controlador->getDispositivosDeLocalCantidad($row['ip3']);
                     $mostrarDispositivosDeLocal = $dispositivosDeLocal->fetch_assoc();
                     echo htmlspecialchars($mostrarDispositivosDeLocal['count(*)']);
                    ?>
                </p>
                <p>VLAN del local: <?php echo htmlspecialchars($row['ip3']);?></p>
                </div><!-- fin de card-local-info-->
                <a class="btn btn-success" href="?mostrar_detalles=<?php echo htmlspecialchars($row['id_locales'])?>">Más Detalles</a>
                </div>
            </div><!-- fin de div de cada card del local -->
                 <?php $iterador= $iterador+1;?>
                
            <?php endwhile; ?>

            </div> <!-- fin del row para los iconos de los locales -->
            </div><!-- fin de la columna para la tabla -->








            <div class="col-1 col-acciones col-acciones-tecnico"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_local=1">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Local"/>
            </a>
            <a href="?editar_local" id="editar-local">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar Local"/>
            </a>
            <a href="?eliminar_local" id="eliminar-local">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Eliminar Local"/>    
            </a>    
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->
            <?php endif;?> <!-- fin de mostrar locales -->
            
            <?php if($condicionLocalesAuxiliar === false):?> <!-- inicio de mostrar dispositivos -->
        <div class="advertencia-dispositivos" style="width: max-content;"><!-- inicio de advertencia-dispositivos -->
        <h1>Aun no hay locales registrados</h1>
        <p>¿Le gustaria agregar un nuevo local ahora?</p>
        <a href="?añadir_local=1" class="btn btn-success">
        Añadir Local
        </a>
        </div><!-- fin de advertencia-dispositivos -->
    <?php endif; ?>



            <?php if($añadirLocal): ?>
            <div class="editar-fondo">  <!-- inicio de añadir dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                <h2 class="bg-success">Añadir Local</h2>
                <form method="POST" action="añadirLocal.php">
                <label for="denominacion">Nombre del local:</label>
                </br><input type="text" name="denominacion" placeholder="Inserte el nombre del local" 
                id="denominacion"class="mb-3 col-9 text-center" required
                <?php if(isset($_POST['denominacion_advertencia'])){ echo "value=".$_POST['denominacion_advertencia'];}?>  />
                </br>
                <label for="ciudad">Ciudad:</label>
                </br><input type="text" id="ciudad" name="ciudad" placeholder="Ciudad perteneciente"
                class="mb-3 col-9 text-center" required
                <?php if(isset($_POST['ciudad_advertencia'])){ echo "value=".$_POST['ciudad_advertencia'];}?>   />
                </br>
                <label for="direccion">Direccion:</label>
                </br><input type="text" id="direccion" name="direccion" placeholder="Introduzca la direccion"
                class="mb-3 col-9 text-center" required
                <?php if(isset($_POST['direccion_advertencia'])){ echo "value=".$_POST['direccion_advertencia'];}?>    />
            </br>
                <label for="ip3">VLAN del local:</label>
            </br>
                <input value=<?php if(isset($_POST['ip3_advertencia'])){ echo $_POST['ip3_advertencia'];}
                else{
                echo 0;}?>
                 type="number" max="255" min="0" name="ip3" id="ip3" 
                placeholder="X.X.Numero.X" required class="col-5 text-center"
                />
            </br>
            
                <button type="submit" class="btn btn-success mb-3">Añadir Local</button>
                </form>
    </div> <!-- fin de la ventana añadir local -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de añadir Local -->
    

            <?php if($editarLocal): ?>
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                <h2 class="bg-success">Editar Local</h2>
                <form method="POST" action="editarLocal.php">
                <input type="hidden" name="id_locales" value="<?php echo htmlspecialchars($editarLocal['id_locales']);?>">
                <label for="denominacion">Nombre del local:</label>
                </br><input type="text" name="denominacion" placeholder="Inserte el nombre del local" 
                id="denominacion"class="mb-3 col-9 text-center" required 
                value=" <?php if(isset($_POST['denominacion_advertencia'])){ echo $_POST['denominacion_advertencia'];}else{echo htmlspecialchars($editarLocal['denominacion']);}?>"  />
                </br>
                <label for="ciudad">Ciudad:</label>
                </br><input type="text" id="ciudad" name="ciudad" placeholder="Ciudad perteneciente" 
                class="mb-3 col-9 text-center" required value="<?php if(isset($_POST['ciudad_advertencia'])){ echo $_POST['ciudad_advertencia'];}else{echo htmlspecialchars($editarLocal['ciudad']);}?>" />
                </br>
                <label for="direccion">Direccion:</label>
                </br><input type="text" id="direccion" name="direccion" placeholder="Introduzca la direccion"
                class="mb-3 col-9 text-center" required value="<?php if(isset($_POST['direccion_advertencia'])){ echo $_POST['direccion_advertencia'];}else{echo htmlspecialchars($editarLocal['direccion']);}?>" />
            </br>
                <label for="ip3">VLAN del local:</label>
            </br>
                <input type="number" max="255" min="0" name="ip3" id="ip3" 
                placeholder="X.X.Numero.X" required class="col-5 text-center"
                value="<?php if(isset($_POST['ip3_advertencia'])){ echo $_POST['ip3_advertencia'];}else{echo htmlspecialchars($editarLocal['ip3']);}?>" />
            </br>
            
                <button type="submit" class="btn btn-success mb-3">Editar Local</button>
                </form>
    </div> <!-- fin de la ventana editar local -->
    </div> <!-- fin de editar-fondo --> 
                <?php endif;?> <!-- fin de editar local -->



            <?php if($eliminarLocal): ?><!-- inicio de eliminar dispositivo -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="<?php echo $_SERVER['HTTP_REFERER']?>">X</a>
                <h2 class="bg-danger">¿Estas seguro de eliminar este local?</h2>
                <form method="POST" action="eliminarLocal.php">
                <input type="hidden" name="id_locales" value="<?php echo htmlspecialchars($eliminarLocal['id_locales']);?>">
                <input type="hidden" name="ip3" value="<?php echo htmlspecialchars($eliminarLocal['ip3']); ?>">
                <input type="hidden" name="denominacion" value="<?php echo htmlspecialchars($eliminarLocal['denominacion']); ?>">
                <label for="denominacion">Nombre del local:</label>
                </br><input type="text" name="denominacion" placeholder="Inserte el nombre del local" 
                id="denominacion"class="mb-3 col-9 text-center" required disabled
                value="<?php echo htmlspecialchars($eliminarLocal['denominacion'])?>"/>
                </br>
                <label for="ciudad">Ciudad:</label>
                </br><input type="text" id="ciudad" name="ciudad" placeholder="Ciudad perteneciente" disabled
                class="mb-3 col-9 text-center" required value="<?php echo htmlspecialchars($eliminarLocal['ciudad'])?>"/>
                </br>
                <label for="direccion">Direccion:</label>
                </br><input type="text" id="direccion" name="direccion" placeholder="Introduzca la direccion" disabled
                class="mb-3 col-9 text-center" required value="<?php echo htmlspecialchars($eliminarLocal['direccion'])?>"/>
            </br>
                <label for="ip3">VLAN del local:</label>
            </br>
                <input type="number" max="255" min="0" name="ip3" id="ip3" 
                placeholder="X.X.Numero.X" required class="col-5 text-center"
                value="<?php echo htmlspecialchars($eliminarLocal['ip3'])?>" disabled/>
            </br>
            
                <button type="submit" class="btn btn-danger mb-3">Eliminar Local</button>
                </form>
    </div> <!-- fin de la ventana borrar local -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif; ?> <!-- fin de eliminar local -->

            <?php if($mostrarDetalles): ?>
            <div class="editar-fondo">  <!-- inicio de mostrar local -->
            <div class="card-mostrar-detalles">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                <h2><?php echo htmlspecialchars($mostrarDetalles['denominacion'])?></h2>
                <div class="row"> <!-- inicio de card-mostrar-detalles-body -->
                    
                <div class="col-lg-9"><!-- columna de detalles-->
                <p>Ciudad: <?php echo htmlspecialchars($mostrarDetalles['ciudad'])?></p>
                <p>Direccion: <?php echo htmlspecialchars($mostrarDetalles['direccion'])?></p>
                <p>VLAN: <bold><?php echo htmlspecialchars($mostrarDetalles['ip3'])?></bold></p>
                <p>Dispositivos registrados: <?php 
                     $dispositivosDeLocal = $controlador->getDispositivosDeLocalCantidad($mostrarDetalles['ip3']);
                     $mostrarDispositivosDeLocal = $dispositivosDeLocal->fetch_assoc();
                     echo htmlspecialchars($mostrarDispositivosDeLocal['count(*)']);
                    ?></p>
                </div><!--fin de columna de detalles -->

                <div class="col-lg-3 align-content-center"><!-- columna imagen -->
                    <img src="/ping-scan/public/media/imagenes/super6.png" alt="Local"
                    style="width:100%;border-radius:10px;border:thin solid;border-radius:10px;margin:2%;max-height: 200px;"/>
                </div><!--fin de columna imagen -->
                </div><!-- fin de card-mostrar-detalles-body -->

                <div class="card-mostrar-detalles-footer"><!-- inicio de card-mostrar-detalles-footer -->
                <a href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php?editar_local=<?php echo htmlspecialchars($mostrarDetalles['id_locales'])?>"
                class="btn btn-primary">Editar</a>
                <a href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php?eliminar_local=<?php echo htmlspecialchars($mostrarDetalles['id_locales'])?>"
                class="btn btn-danger">Eliminar</a>
            <form method="POST" action="/ping-scan/modules/Tecnico/administrar-dispositivos/vista.php">    
            <input type="hidden" name="locales_ip3" value="<?php echo htmlspecialchars($mostrarDetalles['ip3']);?>">
            <button class="btn btn-warning card-mostrar-detalles-mostrar-dispositivos" type="submit">Mostrar Dispositivos</button>
                <form>
            </div>
                </div><!-- fin de card-mostrar-detalles-footer -->
            </div> <!-- fin de la ventana mostrar local -->
            </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de mostrar detalles -->
            













            <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "denominacion duplicada"): 
        $denominacionAdvertencia = htmlspecialchars($_SESSION['error']['denominacion']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                        <h2>Nombre del local duplicado</h2> 
                        <p>Al parecer el nombre para el local: <b style="color:red;"><?php echo $denominacionAdvertencia ?></b> ya esta registrado bajo otro local.</p>
                        <p>Por favor, ingrese otro nombre para el local</p>
                        <a class="btn-danger" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Tecnico/gestionar_locales/vista.php?añadir_local=1';} 
                       else if(isset($_SESSION['error']['origen']) && $_SESSION['error']['origen'] == "editar"){ echo '/ping-scan/modules/Tecnico/gestionar_locales/vista.php?editar_local='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip3_advertencia" value=  <?php echo $_SESSION['error']['ip3'];?>  />
                            <input type="hidden" name="denominacion_advertencia" value=  <?php echo $_SESSION['error']['denominacion'];?>  />
                            <input type="hidden" name="ciudad_advertencia" value=  <?php echo $_SESSION['error']['ciudad'];?>  />
                            <input type="hidden" name="direccion_advertencia" value=  <?php echo $_SESSION['error']['direccion'];?> />
                            <input type="hidden" name="advertencia" value="denominacion duplicada" />
                            <button type="submit" class="btn-primary">Cambiar nombre</button>
                        </form>

                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
              <?php  endif;?> <!-- fin de denominacion duplicada -->  



















            
            <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "ip duplicada"): 
        $ipAdvertencia = htmlspecialchars($_SESSION['error']['ip3']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">X</a>
                        <h2>IP duplicada</h2> 
                        <p>Al parecer la IP: <b style="color:red;"><?php echo $ipAdvertencia ?></b> ya esta registrada bajo otro local.</p>
                        <p>Por favor, ingrese otra IP para el local</p>
                        <a class="btn-danger" href="/ping-scan/modules/Tecnico/gestionar_locales/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Tecnico/gestionar_locales/vista.php?añadir_local=1';} 
                       else if(isset($_SESSION['error']['origen']) && $_SESSION['error']['origen'] == "editar"){ echo '/ping-scan/modules/Tecnico/gestionar_locales/vista.php?editar_local='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip3_advertencia" value=  <?php echo $_SESSION['error']['ip3'];?>  />
                            <input type="hidden" name="denominacion_advertencia" value=  <?php echo $_SESSION['error']['denominacion'];?>  />
                            <input type="hidden" name="ciudad_advertencia" value=  <?php echo $_SESSION['error']['ciudad'];?>  />
                            <input type="hidden" name="direccion_advertencia" value=  <?php echo $_SESSION['error']['direccion'];?> />
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

<?php if($condicionLocalesAuxiliar):?> // inicio de pingear dispositivos

    const tamañoTabla=<?php echo $iterador ?>;
    let n;
    for(n=0;n<tamañoTabla;n++){
        const valorActual=n;
        document.getElementById(valorActual).addEventListener("click",() => {
            //eliminar el foco actual
            if(document.getElementsByClassName("card-local-seleccionado")[0]){
            document.getElementsByClassName("card-local-seleccionado")[0].className="card-local card-local-tecnico";
            }
            //cambiar el foco actual por el elemento seleccionado
            document.getElementById(valorActual).className="card-local-seleccionado";
            //cambiar la ruta del boton para editar
            document.getElementById("editar-local").href="?editar_local="+document.getElementById(valorActual).children[0].id;
            //cambiar la ruta del boton para eliminar
            document.getElementById("eliminar-local").href="?eliminar_local="+document.getElementById(valorActual).children[0].id;
        });
    }
    <?php endif;?> //fin de pingear dispositivos  

    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Tecnico/dashboard/DashboardView.php";})
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)

    //como enfocar en el campo IP cuando hay un error de ip duplicada
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="ip duplicada"):?>
    if(document.getElementById("ip3")){
        document.getElementById("ip3").focus();
        document.getElementById("ip3").value="";
    }
<?php endif;?>

//como enfocar en el campo denominacion cuando hay un error de denominacion duplicada
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="denominacion duplicada"):?>
    if(document.getElementById("denominacion")){
        document.getElementById("denominacion").focus();
        document.getElementById("denominacion").value="";
    }
<?php endif;?>

    </script>
</body>
</html>