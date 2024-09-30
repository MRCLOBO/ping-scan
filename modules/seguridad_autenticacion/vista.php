<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/seguridad_autenticacion/controlador.php';

session_start();
$user = json_decode(json_encode($_SESSION['usuario']));

 //Verificar si el usuario es administrador
    
if (!isset($_SESSION['usuario']) || $user->rol !== 'admin') {
    header("Location: ../../public/login.php");
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
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/componentes/navbar.php"?>
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
                
                <tr id=<?php echo $iterador?> class="fila-datos">
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

            <a href="?añadir_dispositivo=1">
                <img src="/ping-scan/public/media/imagenes/icono-mas.png" alt="Añadir Dispositivo"/>
            </a>
            <a href="?editar_usuario=" id="editar-usuario">
            <img src="/ping-scan/public/media/imagenes/editar.png" alt="Editar dispositivo"/>
            </a>
            <a href="?eliminar_usuario=" id="eliminar-usuario">
            <img src="/ping-scan/public/media/imagenes/icono-eliminar.png" alt="Añadir Dispositivo"/>    
            </a>    
            </div>

            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            </div><!--final del segundo row -->



        <?php if($añadirDispositivo): ?>
            <?php $componentes->ventanaDispositivo();?>
            <?php endif;?> <!-- fin de añadir dispositivo -->
    

            <?php if($editarUsuario): ?>
        <div class="editar-fondo">  <!-- inicio de editar dispositivo -->
            <div class="formulario-añadir-dispositivo">
            <a class="btn bg-danger text-light boton-atras" href="<?php echo $_SERVER['HTTP_REFERER']?>">X</a>
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
                <option value="admin" <?php echo $editarUsuario['rol'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?php echo $editarUsuario['rol'] === 'user' ? 'selected' : ''; ?>>Usuario</option>
                </select>
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
        });
    }
    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/modules/dashboard/DashboardView.php";})
    //Logica para poner el local del usuario de acuerdo a una condicion, y hacerlo obligatorio
    document.getElementById("edit_role").addEventListener("click",()=>{
    if(document.getElementById("edit_role")!==null){
        if(document.getElementById("edit_role").value==="user"){
        //poner al local del usuario obligatorio
        document.getElementById("container-elegir-local").className="elegir-local-mostrar";
        document.getElementById("usuario-local").required=true;
        }
        else{
            document.getElementById("container-elegir-local").className="elegir-local-ocultar";
            document.getElementById("usuario-local").required=false;
        }
    }})//fin de logica para poner el local del usuario
    </script>
</body>
</html>