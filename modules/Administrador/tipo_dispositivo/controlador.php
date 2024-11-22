<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/tipo_dispositivo/modelo.php';

class ControladorTipoDispositivo {
    private $model;
    private $conn;

    public function __construct($conn) {
        $this->model = new ModeloTipoDispositivo($conn);
        $this->conn = $conn;
    }
    public function getTipoDispositivo(){
        return $this->model->getTipoDispositivo();
    }
    public function getDispositivosConTipo($tipo_dispositivo_ip2){
        return $this->model->getDispositivosDeTipoCantidad($tipo_dispositivo_ip2);
    }
    public function añadirTipoDispositivo($equipo,$ip2){
        return $this->model->añadirTipoDispositivo($equipo,$ip2);    
    }
    public function getEditarTipo($id_tipo_dispositivo){
        return $this->model->getTipoId($id_tipo_dispositivo);
    }
    public function editarTipoDispositivo($id_tipo_dispositivo,$equipo,$ip2){
        return $this->model->editarTipoDispositivo($id_tipo_dispositivo,$equipo,$ip2);
    }
    public function editarDispositivosConTipo($id_tipo_dispositivo,$ip2){
        return $this->model->editarDispositivosConTipo($id_tipo_dispositivo,$ip2);
    }
    public function getDispositivosDeTipo($ip2){
        return $this->model->getDispositivosDeTipo($ip2);
    }
    public function eliminarDispositivo($id_dispositivos){
        return $this->model->eliminarDispositivo($id_dispositivos);
    }
    public function eliminarTipo($id_tipo_dispositivo){
        return $this->model->eliminarTipo($id_tipo_dispositivo);
    }
    public function comprobarIP($ip){
        return $this->model->comprobarIP($ip);
    }
    public function comprobarTipo($equipo){
        return $this->model->comprobarTipo($equipo);
    }


/*
    public function localDeDispositivo($ip_local){
        return $this->model->localDeDispositivo($ip_local);
    }


    public function editarDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo){
        return $this->model->updateDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo);
    }
    public function eliminarLocal($id_locales){
        return $this->model->eliminarLocal($id_locales);
    }
    public function eliminarDispositivosDeLocal($ip3){
        return $this->model->eliminarDispositivosDeLocal($ip3);
    }
        //Funcion para oobtener las IDs de los usuarios de un correspondiente local
        public function getUsuariosDelLocal($denominacion){
            return $this->model->getUsuariosDelLocal($denominacion);
        }
        //Eliminar todos los usuarios de un correspondiente local
        public function eliminarUsuarioDeLocal($denominacion){
            return $this->model->eliminarUsuarioDeLocal($denominacion);
        }
        //Eliminar los usuarios de la tabla usuarios,para eliminarlos en conjunto al local

        public function editarLocal($id_usuarios,$denominacion,$ciudad,$direccion,$ip3){
            return $this->model->editarLocal($id_usuarios,$denominacion,$ciudad,$direccion,$ip3);
        }

        public function editarUsuarioLocal($id_locales,$denominacion){
            return $this->model->editarUsuarioLocal($id_locales,$denominacion);
        }
        public function getDispositivosDeLocalCantidad($locales_ip3){
            return $this->model->getDispositivosDeLocalCantidad($locales_ip3);
        }
        public function getDispositivosDeLocal($locales_ip3){
            return $this->model->getDispositivosDeLocal($locales_ip3);
        }
*/
}
?>
