<?php
//Componente reutilizable para añadir un dispositivo
class Componentes{
    public function ventanaDispositivo(){
        $rutaActual= $_SERVER['HTTP_REFERER'];
        echo
            "<div class='editar-fondo'> 
            <div class='formulario-añadir-dispositivo'>
            <a class='btn bg-danger text-light boton-atras' href='$rutaActual'>X</a>
            <h2>Añadir dispositivo</h2>
            <form method='POST' action='añadirDispositivo.php'>
            <label for='ip1'>Ingrese la direccion IP del dispositivo:</label>
            <div class='solicitar-ip'> 
            <input  type='number' max='255' min='0' id='ip1' name='ip1' required/>
            <label for='ip2'>.</label>
            <input type='number' max='255' min='0' id='ip2' name='ip2' required/>
            <label for='ip3'>.</label>
            <input type='number' max='255' min='0' id='ip3' name='ip3' required/>
            <label for='ip4'>.</label>
            <input type='number' max='255' min='0' id='ip4' name='ip4' required/>
            </div>
            </br>
            <label for='nombre_equipo'>Nombre del dispositivo</label>
            </br>
            <input type='text' id='nombre_equipo' name='nombre_equipo'/>
            </br>
            <button type='submit' class='btn btn-primary'>Enviar</button>
            </form>
            </div>
            </div>";
            
    }// fin de ventana Dispositivo



}


?>