<?php $verificarUsuarios = $conn->query("SELECT * FROM usuarios");

if ($verificarUsuarios->num_rows == 0) {
        header("Location: /ping-scan/index.php");
        exit(); // Asegúrate de usar exit() después de header()
}?>
            <div class="row bg-primary p-2 contenedor-navbar" >
            <div class="col-2 col-md-1 text-center">
                <img src="/ping-scan/public/media/imagenes/icono-atras.png"
                class="btn" id="boton-atras" title="Volver a atras"/>
            </div>
            <div class="col-3 col-md-2">
                <!-- dividir en 3 partes -->
                 
                <a class="ping-scan-logo row col-12" title="Menu Principal" 
                href="/ping-scan/modules/Administrador/dashboard/DashboardView.php"> <!-- inicio del row--> 
                    <img class="col-12 col-md-4" src="/ping-scan/public/media/imagenes/ping-scan-logo.jpg" />
                <p class="col-0 col-md-8">Ping-Scan</p>
</a> <!-- fin del row -->
            </div>

            <div class="col-1 col-md-6 ocultar-nav-bar"></div>

                          <!-- inicio info usuario -->
        <div class="col-4 col-md-2" >
            <div class="row">
                <div class="col-3 text-center">
                    <img src="/ping-scan/public/media/imagenes/icono-admin.png" alt="Profile" class="icono" title=" <?php echo htmlspecialchars($user->usuario); ?> "/>     
                </div>
                <div class="col-9">
            <p class="usuario-icono-info"><b><?php echo htmlspecialchars($user->usuario); ?></b></p>  
            <p class="usuario-icono-info"><?php echo htmlspecialchars($user->rol); ?></p>       
                </div>
            </div>
        </div> 
            <!-- Fin de info usuario nav-->


        <div class="col-2 col-md-1 btn-cerrar-seccion">
        <form action="/ping-scan/modules/Administrador/componentes/controlador.php?action=logout" method="POST">
            <button class="btn btn-danger" type="submit" title="Cerrar Sesion">Salir</button>
        </form>
        </div>
    </div>