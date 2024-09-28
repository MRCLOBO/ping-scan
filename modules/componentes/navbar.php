
            <div class="row bg-primary p-2">
            <div class="col col-1 text-center">
                <img src="../../public/media/imagenes/icono-atras.png"
                class="btn" id="boton-atras"/>
            </div>
            <div class="col col-1 text-center">
                <img src="../../public/media/imagenes/icono-hamburguesa.png" alt=""
                class="icono"/>
            </div> 

             <!-- inicio info usuario -->
        <div class="col-3" >
            <div class="row">
                <div class="col-3 text-center">
                    <img src="../../public/media/imagenes/icono-admin.png" alt="Profile" class="icono" />     
                </div>
                <div class="col-9">
            <p class="usuario-icono-info"><b><?php echo htmlspecialchars($user->usuario); ?></b></p>  
            <p class="usuario-icono-info"><?php echo htmlspecialchars($user->rol); ?></p>       
                </div>
            </div>
        </div> 
            <!-- Fin de info usuario nav-->
             <div class="col-5"></div>
        <div class="col-2 btn-cerrar-seccion">
        <form action="DashboardController.php?action=logout" method="POST">
            <button class="btn btn-danger" type="submit">Cerrar Sesión</button>
        </form>
        </div>
    </div>