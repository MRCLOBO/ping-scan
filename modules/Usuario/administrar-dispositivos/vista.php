<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');

/*Inicia la conexion con la base de datos*/ 
$conexion = new Conectar();
$conn = $conexion->getConexion();


/*Requerimiento de controller.php*/
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Usuario/administrar-dispositivos/controlador.php';

// Instanciar la clase de controller.php
$controlador = new ControladorDispositivos($conn);

// Generar una instancia para reutilizar codigo
require  $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Usuario/componentes/componentes.php';
$componentes = new Componentes();

//INICIAR SECCION
session_start();
//Mostrar los locales de manera filtrada
$getLocalDeUsuario = $controlador->getUsuarioLocal($_SESSION['id_usuarios']);
$usuarioLocal = $getLocalDeUsuario->fetch_assoc();
$getLocal = $controlador->getLocal($usuarioLocal['denominacion']);
$_SESSION['local'] = $getLocal['ip3'];
//Guardo el resultado de la consulta de mostrarDispositivos en $dispositivos
if($_SESSION['local'] !== null){ //si tiene el valor de un local mostrara una lista de solamente de ese local
    $dispositivos =$controlador->getDispositivosDeLocal($_SESSION['local']);
    $condicionDispositivos= $controlador->getDispositivosDeLocal($_SESSION['local']);
}else{
    $dispositivos = $controlador->mostrarDispositivos();
    $condicionDispositivos = $controlador->mostrarDispositivos();
    }
$condicionDispositivosAuxiliar = $condicionDispositivos->fetch_assoc() !== null;
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
<?php require_once $_SERVER['DOCUMENT_ROOT']."/ping-scan/modules/Usuario/componentes/navbar.php"?>
    <div class="container"> <!-- Inicio del div principal -->
        <div class="row text-center"><h2>Dispositivos del Local</h2></div>
    <div class="row"><!-- inicio del segundo row -->
        <div class="col-0 col-md-1"></div> <!--columna de relleno -->
    <div class="col col-12 col-lg-9"><!-- inicio de la columna para la tabla-->
        


            <!-- Inicio el cuadro de busqueda -->
    <div class="row">
            <div class="col-8 text-center" style="align-content: center;">
                <input type="text" placeholder="Buscar equipo" id="cuadro_busqueda" title="Introduce el nombre del equipo"/>
            </div>
            <div class="col-1 icono-busqueda"><img src="/ping-scan/public/media/imagenes/icono-lupa.png" title="Busca el equipo"/></div>
    </div>
            <!-- Fin del cuadro de busqueda-->



    <?php if($condicionDispositivosAuxiliar === false):?> <!-- inicio de mostrar dispositivos -->
        <div class="advertencia-dispositivos"><!-- inicio de advertencia-dispositivos -->
        <h1>Aun no hay dispositivos registrados dentro de este local</h1>
        </div><!-- fin de advertencia-dispositivos -->
    <?php endif; ?>
    <?php if($condicionDispositivosAuxiliar === true):?> <!-- inicio de mostrar dispositivos -->
    <table class="tabla-monitorear-dispositivos tabla-monitorear-dispositivos-usuario">
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
   

            <?php endif; ?> <!-- fin de mostrar dispositivos -->
    </div><!-- Fin del div principal -->
 <div class="col-1 col-acciones-usuario"> <!-- inicio de la columna para las herramientas -->
            <?php if($_SESSION['local'] !== null): ?>   
            <a class="btn" onclick="generarInforme()"  id="generar-documento" title="Genera un documento del estado actual de todos los equipos">
            <img src="/ping-scan/public/media/imagenes/documento.png" alt="Generar Documento"/>    
            </a>
            <?php endif; ?> 
            </div>

            </div><!-- fin de la columna para la tabla -->


            <p id="auxiliar-iterador" style="z-index:-10;position:fixed;color:transparent"><?php echo $iterador ?></p>
            <p id="os" style="z-index:-10;position:fixed;color:transparent"><?php echo $_SESSION['OS']?></p>    
        </div><!--final del segundo row -->
    <?php if($condicionDispositivosAuxiliar):?> <!-- inicio de pingear dispositivos-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script  src="script.js" type="module">
    </script>
    <?php endif;?><!-- fin de pingear dispositivos -->
    <script>

    //Boton atras
    document.getElementById("boton-atras").addEventListener("click",() =>{window.location.href = "/ping-scan/public/login.php";})
    
    setInterval(()=>{
    document.getElementById("notificacion").className="notificacion-desaparecer"
    },3000)


     //funcion para poder realizar la busqueda de un dispositivo 
     let cuadroBusqueda = document.getElementById("cuadro_busqueda");
    cuadroBusqueda.addEventListener("change", (e) =>{ //evento "change" en este caso cada vez que el elemento pierda el foco ejecutara la funcion
        for(i=0;i< Number(document.getElementById("auxiliar-iterador").textContent);i++){
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
    <script src="http://localhost/ping-scan/modules/Administrador/informes/informe.js"></script>
</body>
</html>