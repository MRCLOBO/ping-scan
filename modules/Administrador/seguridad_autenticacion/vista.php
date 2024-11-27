<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/controlador.php';

session_start();
$user = json_decode(json_encode($_SESSION['usuario']));

 //Verificar si el usuario es administrador
    
if (!isset($_SESSION['usuario']) || $user->rol !== 'admin') {
    header("Location: ../../../public/login.php");
    exit();
}


$conexion = new Conectar();
$conn = $conexion->getConexion();

$controlador = new ControladorUsuarios($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controlador->handleRequest();
}

$editarUsuario = null;
if (isset($_GET['editar_usuario'])) {
    $editarUsuario = $controlador->getUserToEdit($_GET['editar_usuario']);
}
$añadirUsuario=null;
if (isset($_GET['añadir_usuario'])) {
    $añadirUsuario = true;
}
$eliminarUsuario=null;
if (isset($_GET['eliminar_usuario'])) {
    $eliminarUsuario = $controlador->getUserToEdit($_GET['eliminar_usuario']);
}
$restaurarContrasena = null;
if (isset($_GET['restaurar_contrasena'])) {
    $usuario_restaurarContrasena =  $controlador->getUserToEdit($_GET['restaurar_contrasena']);
    $restaurarContrasena = true;
}

$locales = $controlador->getLocales();
$usuarios = $controlador->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="/ping-scan/public/css/personalizado.css">
    <link rel="stylesheet" href="/ping-scan/public/css/bootstrap-5.0.2-dist/css/bootstrap.css">
</head>
<body class="bg-dark text-light">
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/notificaciones.php";
#Despues de llamar a la notificacion se limpia la variable para que en cada renderizado no vuelva a aparecer
$_SESSION['notificacion']="";?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Administrador/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Administracion de Usuarios</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
    <table class="tabla-monitorear-dispositivos">
        <thead >
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Rol</th>
            </tr>
        </thead>
        <?php 
        $iterador=0;
        ?>
        <tbody >
            <?php while ($row = $usuarios->fetch_assoc()):?>
                
                <tr id=<?php echo $iterador?>  title="Selecciona el usuario">
                    <td id=<?php echo htmlspecialchars($row['id_usuarios']);?>><?php echo htmlspecialchars($row['id_usuarios']);?></</td>
                    <td><?php echo htmlspecialchars($row['usuario']);?></td>
                    <td><?php echo htmlspecialchars($row['nombre']);?></td>
                    <td><?php echo htmlspecialchars($row['rol']);?></td>
                </tr>
                 <?php $iterador= $iterador+1;?>
                
            <?php endwhile; ?>
            </tbody>
    </table>
            </div><!-- fin de la columna para la tabla -->

            <div class="col-1 col-acciones"> <!-- inicio de la columna para las herramientas -->

            <a href="?añadir_usuario=1" title="Añade un nuevo usuario">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Usuario"/>
            </a>
            <a href="?editar_usuario=" id="editar-usuario" title="Edita la informacion de un usuario">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar Usuario"/>
            </a>
            <a href="?eliminar_usuario=" id="eliminar-usuario" title="Elimina a un usuario">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Eliminar Usuario"/>    
            </a>
            <a href="?restaurar_contrasena" id="restaurar-contrasena" title="Restaura la contraseña del usuario seleccionado">
            <img src="/ping-scan/public/media/imagenes/icono-desbloquear.png" alt="Desbloqueo"/>    
            </a> 
             <a href="http://localhost/ping-scan/modules/Administrador/informes/vista_usuarios.php" target="_blank" id="generar-documento">
            <img src="/ping-scan/public/media/imagenes/documento.png" alt="Generar Documento"/>    
            </a>    
            </div>
            

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->





        <?php if($añadirUsuario):?>
            <div class="editar-fondo">  <!-- inicio de añadir dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">X</a>
                <h2>Añadir Usuario</h2>
                <form method="POST" action="añadirUsuario.php">
                <label for="usuario">Usuario:</label>
                </br><input type="text" name="usuario" placeholder="Inserte el usuario" 
                id="usuario"class="mb-3 col-11 col-md-6 text-center" required
                <?php if(isset($_POST['usuario_advertencia'])){echo 'value='.$_POST['usuario_advertencia'];}?>  />
                </br>
                <label for="nombre">Nombre:</label>
                </br><input type="text" id="nombre" name="nombre" placeholder="Inserte el nombre"
                class="mb-3 col-11 col-md-6 text-center" required
                <?php if(isset($_POST['nombre_advertencia'])){echo 'value='.$_POST['nombre_advertencia'];}?>     />
                </br>
                <label>Contraseña:</label>
                </br><input type="password" id="contrasena" name="contrasena" placeholder="Introduzca una contraseña"
                class="mb-3 col-11 col-md-6 text-center" required />
        </br>

                <label for="edit_role">Rol:</label>
            </br>
                <select id="edit_role" name="rol" class="mb-3" required>
                <option value="admin"  <?php if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'admin'){echo 'selected' ;} ?> >Administrador</option>
                <option value="tecnico"   <?php if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'tecnico'){echo 'selected' ;} ?>  >Técnico</option>
 <?php 
                $verificarLocal = $conn->query("SELECT * FROM locales");
                if ($verificarLocal->num_rows != 0) {
                    $agregado = "";
                    if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'user'){$agregado='selected';}
                        echo "<option value='user'".$agregado.">Usuario</option>" ;
                }
?>
                </select>
            </br>
            <!-- Local perteneciente del usuario -->

            <div id="container-elegir-local" class="elegir-local-ocultar">
            <label for="usuario-local">Especificar el local del usuario:</label>
            </br><select name="usuario_local" class="mb-3" id="usuario-local">
                <?php while ($rowEditar = $locales->fetch_assoc()):?>
                    <option value="<?php echo $rowEditar['denominacion']?>"
                    <?php if(isset($_POST['usuario_local_advertencia']) &&  $_POST['usuario_local_advertencia'] == $rowEditar['denominacion']){echo 'selected';}?>   > <?php echo $rowEditar['denominacion']?> </option>
                <?php endwhile; ?>
            </select>
            </div>
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                </form>
    </div> <!-- fin de la ventana añadir usuario -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de añadir dispositivo -->
    




            <?php if($editarUsuario): ?>
        <div class="editar-fondo">  <!-- inicio de editar dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">X</a>
                <h2>Editar informacion</h2>
                <form method="POST" action="editarUsuario.php">
                <input type="hidden" name="id_usuarios" value="<?php echo htmlspecialchars($editarUsuario['id_usuarios']); ?>">
                <label for="usuario">Usuario:</label>
                </br><input type="text" name="usuario" placeholder="Inserte el usuario" 
                id="usuario" value="<?php echo htmlspecialchars($editarUsuario['usuario']); ?>"
                class="mb-3 col-11 col-md-6 text-center" required/>
                </br>
                <label for="nombre">Nombre:</label>
                </br><input type="text" id="nombre" name="nombre" placeholder="Inserte el nombre"
                value="<?php echo htmlspecialchars($editarUsuario['nombre'])?>"
                class="mb-3 col-11 col-md-6 text-center" required/>
                </br>
                <label for="edit_role">Rol:</label>
            </br>
                <select id="edit_role" name="rol" class="mb-3" required>
                <option value="admin"  <?php if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'admin'){echo 'selected' ;} ?> >Administrador</option>
                <option value="tecnico"   <?php if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'tecnico'){echo 'selected' ;} ?>  >Técnico</option>
 <?php 
                $verificarLocal = $conn->query("SELECT * FROM locales");
                if ($verificarLocal->num_rows != 0) {
                    $agregado = "";
                    if(isset($_POST['rol_advertencia']) && $_POST['rol_advertencia'] == 'user'){$agregado='selected';}
                        echo "<option value='user'".$agregado.">Usuario</option>" ;
                }
?></select>
            </br>
            <!-- Local perteneciente del usuario -->
            <div id="container-elegir-local" class="elegir-local-ocultar">
            <label for="usuario-local">Especificar el local del usuario:</label>
            </br><select name="usuario_local" class="mb-3" id="usuario-local">
                <?php while ($rowEditar = $locales->fetch_assoc()):?>
                    <option value="<?php echo $rowEditar['denominacion']?>"><?php echo $rowEditar['denominacion']?></option>
                <?php endwhile; ?>
            </select>
            </div>
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                </form>
    </div> <!-- fin de la ventana editar usuario -->
    </div> <!-- fin de editar-fondo --> 
            <?php endif;?> <!-- fin de editar usuario -->





            <?php if($restaurarContrasena): ?><!-- inicio de eliminar usuario -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">X</a>
                <h3 class="p-3 bg-danger">¿Esta seguro que quiere restaurar la contraseña actual?</h3>
                <form method="POST" action="restaurarContrasena.php">
                <input type="hidden" name="id_usuarios" 
                value="<?php echo $usuario_restaurarContrasena['id_usuarios'];?>" >
                <p class="m-3">Al realizar esta accion el usuario seleccionado tendra la contraseña por defecto "password" la cual debera de ser cambiada una vez este lo utilice</p>
                <a class="btn bg-light m-3" href="<?php echo $_SERVER['HTTP_REFERER']?>">Volver</a>
                <button type="submit" class="btn btn-danger m-3">Restaurar contraseña</button>
                </form>
    </div>
    </div> 
            <?php endif; ?> <!-- fin de eliminar usuario -->
            




            <?php if($eliminarUsuario): ?><!-- inicio de restaurar contrasena -->
                <div class="editar-fondo">
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-dark text-light boton-atras" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">X</a>
                <h3 class="p-3 bg-danger">Eliminar Usuario</h3>
                <form method="POST" action="eliminarUsuario.php">
                <input type="hidden" name="id_usuarios" 
                value="<?php echo htmlspecialchars($eliminarUsuario['id_usuarios']); ?>" >
                <input type="hidden" name="rol" 
                value="<?php echo htmlspecialchars($eliminarUsuario['rol']); ?>" >
                <input type="hidden" name="usuario" 
                value="<?php echo htmlspecialchars($eliminarUsuario['usuario']); ?>" >

                <label for="usuario">Usuario:</label>
                </br><input type="text" name="usuario_" placeholder="Inserte el usuario" 
                id="usuario" value="<?php echo htmlspecialchars($eliminarUsuario['usuario']); ?>"
                class="mb-3 col-11 col-md-6 text-center" required disabled/>
                </br>

                <label for="nombre">Nombre:</label>
                </br><input type="text" id="nombre" name="nombre" placeholder="Inserte el nombre"
                value="<?php echo htmlspecialchars($eliminarUsuario['nombre'])?>"
                class="mb-3 col-11 col-md-6 text-center" required disabled/>
                </br>
                
                <label for="edit_role">Rol:</label>
            </br>
                <select id="edit_role" name="rol" class="p-1 mb-3" required disabled>
                <option value="admin" <?php echo $eliminarUsuario['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="tecnico" <?php echo $eliminarUsuario['rol'] === 'tecnico' ? 'selected' : ''; ?>>Tecnico</option>
                <option value="user" <?php echo $eliminarUsuario['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
                </select>
            </br>
            <!-- Local perteneciente del usuario -->
            <label for="usuario-local">Local del usuario:</label>
            </br><input id="usuario-local" name="usuario_local" class="text-center p-1"
            value="<?php
            if($eliminarUsuario['rol'] === 'user'){
                $usuarioLocal = $controlador->getUsuarioLocal($eliminarUsuario['id_usuarios']);
                $eliminarUsuarioLocal = htmlspecialchars($usuarioLocal['denominacion']);
                echo $eliminarUsuarioLocal;
            }else{
                echo "Sin local fijo";
            }
            ?>" disabled/>
        </br>
    <p>¿Estas Seguro de que deseas eliminar el usuario?</p>
                <button type="submit" class="btn btn-danger mb-3">Enviar</button>
                </form>
    </div>
    </div> 
            <?php endif; ?> <!-- fin de restaurar contrasena -->













            <?php if($_SESSION['error'] !== null && $_SESSION['error']['error'] == "usuario duplicado"): 
        $usuarioAdvertencia = htmlspecialchars($_SESSION['error']['usuario']) ?><!-- INICIO DE NO EXISTE TIPO -->
                <div class="advertencia-fondo-activo"><!-- inicio del cuadro principal-->
                <div class="editar-fondo">
                    <div class="advertencia"><!--inicio de advertencia -->
                    <a class="btn-dark text-light boton-atras" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">X</a>
                        <h2>Nombre de usuario duplicado</h2> 
                        <p>Al parecer el nombre de usuario: <b style="color:red;"><?php echo $usuarioAdvertencia ?></b> ya esta registrado.</p>
                        <p>Por favor, ingrese otro nombre</p>
                        <a class="btn-danger" href="/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php">Cancelar</a>
                        <form action= 
                        <?php if(isset($_SESSION['error']['origen'])  && $_SESSION['error']['origen'] == 'anadir'){ echo '/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php?añadir_usuario=1';} 
                       else if(isset($_SESSION['error']['origen']) && $_SESSION['error']['origen'] == "editar"){ echo '/ping-scan/modules/Administrador/seguridad_autenticacion/vista.php?editar_usuario='.htmlspecialchars($_SESSION['error']['id']);}?>  
                        method="POST">
                            <input type="hidden" name="usuario_advertencia" value=  <?php echo $_SESSION['error']['usuario'];?>  />
                            <input type="hidden" name="nombre_advertencia" value=  <?php echo $_SESSION['error']['nombre'];?>  />
                            <input type="hidden" name="rol_advertencia" value=  <?php echo $_SESSION['error']['rol'];?>  />
                            <input type="hidden" name="usuario_local_advertencia" value=  <?php if(isset($_SESSION['error']['usuario_local'])){echo $_SESSION['error']['usuario_local'];}?> />
                            <input type="hidden" name="advertencia" value="usuario duplicado" />
                            <button type="submit" class="btn-primary">Cambiar nombre de usuario</button>
                        </form>

                    </div><!-- fin de advertencia -->
                </div>
                </div><!-- fin del cuadro principal-->
              <?php  endif;
              $_SESSION['error']=null;?> <!-- fin de denominacion duplicada -->  













    </div><!-- Fin del div principal -->
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
            document.getElementById("editar-usuario").href="?editar_usuario="+document.getElementById(valorActual).children[0].id;
            //cambiar la ruta del boton para eliminar
            document.getElementById("eliminar-usuario").href="?eliminar_usuario="+document.getElementById(valorActual).children[0].id;
             //cambiar la ruta del boton para restaurar contraseña
             document.getElementById("restaurar-contrasena").href="?restaurar_contrasena="+document.getElementById(valorActual).children[0].id;

        });
    }
    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/Administrador/dashboard/DashboardView.php";})
    //Logica para poner el local del usuario de acuerdo a una condicion, y hacerlo obligatorio
    if(document.getElementById("edit_role")!==null){
    document.getElementById("edit_role").addEventListener("click",()=>{
        if(document.getElementById("edit_role").value==="user"){
        //poner al local del usuario obligatorio
        document.getElementById("container-elegir-local").className="elegir-local-mostrar";
        document.getElementById("usuario-local").required=true;
        }
        else{
            document.getElementById("container-elegir-local").className="elegir-local-ocultar";
            document.getElementById("usuario-local").required=false;
        }
    })}//fin de logica para poner el local del usuario
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)
// enfocar al cuadro de usuario si es que hay un nombre duplicado
    <?php if(isset($_POST['advertencia']) && $_POST['advertencia']=="usuario duplicado"):?>
    if(document.getElementById("usuario")){
        document.getElementById("usuario").focus();
        document.getElementById("usuario").value="";
    }
<?php endif;?>
    </script>
</body>
</html>