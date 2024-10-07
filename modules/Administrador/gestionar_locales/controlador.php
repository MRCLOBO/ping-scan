<?php
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/gestionar_locales/modelo.php';

class ControladorLocales {
    private $model;
    private $conn;

    public function __construct($conn) {
        $this->model = new ModeloLocales($conn);
        $this->conn = $conn;
    }
    public function getLocales(){
        return $this->model->getLocales();
    }

    public function localDeDispositivo($ip_local){
        return $this->model->localDeDispositivo($ip_local);
    }
    public function añadirLocal($denominacion,$ciudad,$direccion,$ip3){
        return $this->model->añadirLocal($denominacion,$ciudad,$direccion,$ip3);    
    }

    public function getEditarLocal($id_locales){
        return $this->model->getLocalId($id_locales);
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
        public function eliminarUsuario($id_usuarios){
            return $this->model->eliminarUsuario($id_usuarios);
    }
}
?>
