<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php ?? */
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/administrar-dispositivos/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorDispositivos($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/componentes/componentes.php';
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
 <?php require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/componentes/navbar.php';?> <!-- llamada al navbar -->
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de Dispositivos</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->

    
        <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
        <div class="row">
            <div class="col-8 text-center" style="align-content: center;">
            
                <input type="text" placeholder="Buscar equipo" id="cuadro_busqueda" title="Ingrese el nombre del equipo que desea encontrar"/>
            </div>
            <div class="col-1 icono-busqueda" title="Buscar equipo"><img src="/ping-scan/public/media/imagenes/icono-lupa.png"/></div>
            <div class="col-2 text-center btn-filtrar">
            <a href="?filtrar_dispositivos=1" class="btn-warning" title="Filtrar dispositivos">Filtrar</a>
            </div>
            <!-- Fin del cuadro de busqueda-->
        </div>

    <?php if($condicionDispositivosAuxiliar):?> <!-- inicio de mostrar dispositivos -->
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
                
                <tr id=<?php echo $iterador?> class="fila-datos" title="Seleccionar dispositivo">
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

            <a href="?añadir_dispositivo=1" title="Añadir dispositivo">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Dispositivo"/>
            </a>
            <a href="?editar_dispositivo=" id="editar-dispositivo" title="Editar dispositivo">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar dispositivo"/>
            </a>
            <a href="?eliminar_dispositivo=>" id="eliminar-dispositivo" title="Eliminar dispositivo">
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
        <h1>Aun no hay dispositivos registrados</h1>
        <p>¿Le gustaria agregar un nuevo dispositivo ahora?</p>
        <a href="?añadir_dispositivo=1" class="btn btn-success">
                Añadir Dispositivo
        </a> 
        </div><!-- fin de advertencia-dispositivos -->
    <?php endif; ?>


        <?php if($añadirDispositivo): ?>
            <div class='editar-fondo'> 
            <div class='formulario-añadir-dispositivo'>
            <a class='btn bg-danger text-light boton-atras' href='/ping-scan/modules/Administrador/administrar-dispositivos/vista.php'>X</a>
            <h2>Añadir dispositivo</h2>
            <form method='POST' action='añadirDispositivo.php'>
            <label for='ip1'>Ingrese la direccion IP del dispositivo:</label>
            <div class='solicitar-ip'> 
            <input  type='number' max='255' min='0' id='ip1' name='ip1' <?php if(isset($_POST['ip1_advertencia'])){ echo "value=".$_POST['ip1_advertencia'];}?> required  />
            <label for='ip2'>.</label>
            <input type='number' max='255' min='0' id='ip2' name='ip2' <?php if(isset($_POST['ip2_advertencia'])){ echo "value=".$_POST['ip2_advertencia'];}?>  required/>
            <label for='ip3'>.</label>
            <input type='number' max='255' min='0' id='ip3' name='ip3' <?php if(isset($_POST['ip3_advertencia'])){ echo "value=".$_POST['ip3_advertencia'];}?>  required/>
            <label for='ip4'>.</label>
            <input type='number' max='255' min='0' id='ip4' name='ip4' <?php if(isset($_POST['ip4_advertencia'])){ echo "value=".$_POST['ip4_advertencia'];}?>  required/>
            </div>
            </br>
            <label for='nombre_equipo'>Nombre del dispositivo</label>
            </br>
            <input type='text' id='nombre_equipo' name='nombre_equipo' <?php if(isset($_POST['nombre_equipo_advertencia'])){ echo "value=".$_POST['nombre_equipo_advertencia'];}?> />
            </br>
            <button type='submit' class='btn btn-primary mb-3'>Enviar</button>
            </form>
            </div>
            </div>
            <?php endif;?> <!-- fin de añadir dispositivo -->
    

            <?php if($editarDispositivo): ?>
        <div class="editar-fondo">  <!-- inicio de editar dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href='/ping-scan/modules/Administrador/administrar-dispositivos/vista.php'>X</a>
                <h2>Editar dispositivo</h2>
                <form method="POST" action="editarDispositivo.php">
                <input type="hidden" name="id_dispositivos" value="<?php echo htmlspecialchars($editarDispositivo['id_dispositivos']); ?>">
                <label for="ip1">Direccion IP del dispositivo:</label>
                <div class="solicitar-ip"><!-- poner la ip completa -->
                <input  type="number" max="255" min="0" id="ip1" name="ip1" required
                value="<?php  if(isset($_POST['ip1_advertencia'])){ echo $_POST['ip1_advertencia'];
                }else{ echo $editarDispositivo['ip1'];}
               ?>"  />
                <label for="ip2">.</label>
                <input type="number" max="255" min="0" id="ip2" name="ip2" required
                value="<?php if(isset($_POST['ip2_advertencia'])){ echo $_POST['ip2_advertencia'];
                }else{ echo htmlspecialchars($editarDispositivo['tipo_dispositivo_ip2']);
                }?>" />
                <label for="ip3">.</label>
                <input type="number" max="255" min="0" id="ip3" name="ip3" required
                value="<?php if(isset($_POST['ip3_advertencia'])){ echo $_POST['ip3_advertencia'];
                }else{ echo htmlspecialchars($editarDispositivo['locales_ip3']);
                }?>" />
                <label for="ip4">.</label>
                <input type="number" max="255" min="0" id="ip4" name="ip4" required
                value="<?php if(isset($_POST['ip4_advertencia'])){ echo $_POST['ip4_advertencia'];
                }else{ echo htmlspecialchars($editarDispositivo['ip4']);
                }?>" />
                </div> <!--fin de poner la ip completa -->
    </br>
                <label for="nombre_equipo">Nombre del dispositivo</label>
                </br>
                <input type="text" id="nombre_equipo" name="nombre_equipo"
                value="<?php echo htmlspecialchars($editarDispositivo['nombre_equipo'])?>"/>
    </br>
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                </form>
    </div>
    </div> 
            <?php endif;?> <!-- fin de editar dispositivo -->

            <?php if($eliminarDispositivo): ?><!-- inicio de eliminar dispositivo -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href='/ping-scan/modules/Administrador/administrar-dispositivos/vista.php'>X</a>
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
                <button type="submit" class="btn btn-danger mb-3">Enviar</button>
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




    <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "no existe tipo"): 
        $ip2Advertencia = htmlspecialchars($_SESSION['error']['ip2']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">X</a>
                        <h2>Tipo de dispositivo no registrado</h2> 
                        <p>Al parecer la IP:<b style="color:red;"><?php echo $ip2Advertencia ?></b> aun no ha sido registrado bajo ningun tipo de equipo.</p>
                        <p>¿Te gustaria registrar un nuevo tipo de equipo con esta IP?</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">Cancelar</a>
                        <form action=
                         <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?añadir_dispositivo=1';} 
                        else if(isset($_SESSION['error']['id'])){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?editar_dispositivo='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip1_advertencia" value=  <?php echo $_SESSION['error']['ip1'];?>  />
                            <input type="hidden" name="ip2_advertencia" value=  <?php echo $_SESSION['error']['ip2'];?>  />
                            <input type="hidden" name="ip3_advertencia" value=  <?php echo $_SESSION['error']['ip3'];?>  />
                            <input type="hidden" name="ip4_advertencia" value=  <?php echo $_SESSION['error']['ip4'];?>  />
                            <input type="hidden" name="nombre_equipo_advertencia" value=  <?php echo $_SESSION['error']['nombre_equipo'];?>  />
                            <input type="hidden" name="advertencia" value="tipo dispositivo no encontrado" />
                            <button type="submit" class="btn-primary">Cambiar IP</button>
                        </form>


                        <form action="/ping-scan/modules/Administrador/tipo_dispositivo/vista.php?añadir_tipo=1" method="POST">
                            <input type="hidden" name="ip2_advertencia" value=<?php echo $_SESSION['error']['ip2'];?> />
                            <input type="hidden" name="advertencia" value="tipo dispositivo no encontrado" />
                            <button type="submit" class="btn-success">Agregar nuevo tipo de dispositivo</button>
                        </form>
                        
                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
<?php endif;?>





                <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "no existe local"): 
        $ip3Advertencia = htmlspecialchars($_SESSION['error']['ip3']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">X</a>
                        <h2>Local no registrado</h2> 
                        <p>Al parecer la IP: <b style="color:red;"><?php echo $ip3Advertencia ?></b> aun no ha sido registrado bajo ningun local.</p>
                        <p>¿Te gustaria registrar un nuevo local con esta IP?</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?añadir_dispositivo=1';} 
                        else if(isset($_SESSION['error']['id'])){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?editar_dispositivo='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="ip1_advertencia" value=  <?php echo $_SESSION['error']['ip1'];?>  />
                            <input type="hidden" name="ip2_advertencia" value=  <?php echo $_SESSION['error']['ip2'];?>  />
                            <input type="hidden" name="ip3_advertencia" value=  <?php echo $_SESSION['error']['ip3'];?>  />
                            <input type="hidden" name="ip4_advertencia" value=  <?php echo $_SESSION['error']['ip4'];?>  />
                            <input type="hidden" name="nombre_equipo_advertencia" value=  <?php echo $_SESSION['error']['nombre_equipo'];?>  />
                            <input type="hidden" name="advertencia" value="local no encontrado" />
                            <button type="submit" class="btn-primary">Cambiar IP</button>
                        </form>


                        <form action="/ping-scan/modules/Administrador/gestionar_locales/vista.php?añadir_local=1" method="POST">
                            <input type="hidden" name="ip3_advertencia" value=<?php echo $_SESSION['error']['ip3'];?> />
                            <input type="hidden" name="advertencia" value="local no encontrado" />
                            <button type="submit" class="btn-success">Agregar nuevo local</button>
                        </form>
                        
                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
                
    
            <?php  endif; ?>
<?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "ip duplicada"): 
        $ipAdvertencia = htmlspecialchars($_SESSION['error']['ip1']).".".htmlspecialchars($_SESSION['error']['ip2']).".".htmlspecialchars($_SESSION['error']['ip3']).".".htmlspecialchars($_SESSION['error']['ip4']) ?> <!-- INICIO DE IP DUPLICADA -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">X</a>
                        <h2>IP ya registrada</h2> 
                        <p>Al parecer la IP: <b style="color:red;"><?php echo $ipAdvertencia ?></b> ya ha sido registrada a un equipo.</p>
                        <p>Ingrese una IP diferente.</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/administrar-dispositivos/vista.php">Cancelar</a>
                        <form action=
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?añadir_dispositivo=1';} 
                        else if(isset($_SESSION['error']['id'])){ echo '/ping-scan/modules/Administrador/administrar-dispositivos/vista.php?editar_dispositivo='.htmlspecialchars($_SESSION['error']['id']);}?>   
                        method="POST">
                            <input type="hidden" name="nombre_equipo_advertencia" value=  <?php echo $_SESSION['error']['nombre_equipo'];?>  />
                            <input type="hidden" name="advertencia" value="ip duplicada" />
                            <button type="submit" class="btn-primary">Cambiar IP</button>
                        </form>
                        
                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
<?php endif;?>









        <?php $_SESSION['error']=null;?> <!-- fin de mostrar detalles -->







    </div><!-- Fin del div principal -->


    <?php if($condicionDispositivosAuxiliar):?> <!-- inicio de pingear dispositivos-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script  src="script.js" type="module"></script>

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
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Administrador/dashboard/DashboardView.php";})
    
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


//como enfocar en el campo IP2 cuando hay un error en tipo de dispositivo
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="tipo dispositivo no encontrado"):?>
    if(document.getElementById("ip2")){
        document.getElementById("ip2").focus();
        document.getElementById("ip2").value="";
    }
<?php endif;?>

//como enfocar en el campo IP3 cuando hay un error en local
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="local no encontrado"):?>
    if(document.getElementById("ip3")){
        document.getElementById("ip3").focus();
        document.getElementById("ip3").value="";
    }
<?php endif;?>
//como enfocar en el campo IP1 cuando hay un error de ip duplicada
<?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="ip duplicada"):?>
    if(document.getElementById("ip1")){
        document.getElementById("ip1").focus();
    }
<?php endif;?>

//funcion para poner la IP
function siguienteSegmento(ip){
    if(document.getElementById("ip"+ip)!==null){
    document.getElementById("ip"+ip).addEventListener("keydown", function(event) {
    	if(event.key === "."){
    		document.getElementById("ip"+(ip+1)).focus()
    	}
    })
    document.getElementById("ip"+(ip+1)).addEventListener("keyup", function(event) {
    	if(event.key === "."){
    		document.getElementById("ip"+(ip+1)).value=""
    	}
    })
    }//fin de la condicion if
    }
    //funcion para pasar al anterior segmento al borrar la IP
    function anteriorSegmento(ip){
        if(document.getElementById("ip"+ip)!==null){
    document.getElementById("ip"+ip).addEventListener("keydown", function(event) {
        const valorActual = document.getElementById("ip"+ip).value;
    	if(valorActual === "" && event.key === "Backspace"){
    		document.getElementById("ip"+(ip-1)).focus()
    	}
    })
}//fin de la condicion if
    }

    siguienteSegmento(1);siguienteSegmento(2);siguienteSegmento(3);
    anteriorSegmento(4);anteriorSegmento(3);anteriorSegmento(2);


    </script>
</body>
</html>
