<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping scan</title>
    <?php require_once "./Dependencias.php"?>
    
</head>
<body class="bg-dark">
<img src="../media/imagenes/fondo-inicio-seccion.jpg" alt=""
    class="banner-inicio-seccion"
    />
<main class="container">
   
    <div class="row fila-inicio-seccion">

    <div class="col-12 col-sm-12 col-md-6">
        <div class="card imagen-usuario">
            <img src="../media/imagenes/imagen-usuario.png" alt="usuario"/>
        </div>
        <div class="card card-body">
            <form >
            <div class="card-title"><h2>INICIO DE SECCION</h2></div>
            <div class="card-body">
                <p><label for="usuario">Usuario: </label>
                <input name="usuario" id="usuario"
                type="text" placeholder="Ingrese su usuario"/>
</p><p>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" 
                name="contrasena" placeholder="Ingrese su contraseña"
                />
</p>
            </div>
            <div class="card-footer"><button class="btn btn-primary">Iniciar Seccion</button></div>
            </form>
        </div>
    </div>
    
    </div>
</main>
</body>
</html>