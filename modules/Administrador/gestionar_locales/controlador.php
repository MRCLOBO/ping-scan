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
    public function añadirDispositivo($ip1,$ip2,$ip3,$ip4,$nombre_equipo){
        return $this->model->añadirDispositivo($ip1,$ip2,$ip3,$ip4,$nombre_equipo);    
    }

    public function getEditarLocal($id_locales){
        return $this->model->getLocalId($id_locales);
    }
    public function editarDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo){
        return $this->model->updateDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo);
    }
    public function eliminarDispositivo($id_dispositivos){
        return $this->model->eliminarDispositivo($id_dispositivos);
    }
}
?>
