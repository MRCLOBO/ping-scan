<?php
class ModeloTipoDispositivo {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getTipoDispositivo() {
        return $this->conn->query("SELECT * FROM tipo_dispositivo");
    }
    public function getDispositivosDeTipoCantidad($tipo_dispositivo_ip2){
        $stmt = $this->conn->prepare("SELECT count(*) from dispositivos where tipo_dispositivo_ip2 = ?");
        $stmt->bind_param("i", $tipo_dispositivo_ip2);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function añadirTipoDispositivo($equipo,$ip2){
        $stmt = $this->conn->prepare("INSERT INTO tipo_dispositivo(equipo,ip2) VALUES(? ,?)");
        $stmt->bind_param("si", $equipo, $ip2);
        $stmt->execute();
        
    }
    public function getTipoID($id_tipo_dispositivo){
        $stmt = $this->conn->prepare("SELECT * FROM tipo_dispositivo WHERE id_tipo_dispositivo = ?");
        $stmt->bind_param("i", $id_tipo_dispositivo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function editarTipoDispositivo($id_tipo_dispositivo,$equipo,$ip2){
        $stmt = $this->conn->prepare("UPDATE tipo_dispositivo SET  equipo= ?,  ip2 = ?
        WHERE id_tipo_dispositivo = ?");
        $stmt->bind_param("sii", $equipo,$ip2,$id_tipo_dispositivo);
        return $stmt->execute();
    }
    public function editarDispositivosConTipo($id_tipo_dispositivo,$ip2){
        $stmt = $this->conn->prepare("UPDATE dispositivos SET  tipo_dispositivo_ip2= ?
        WHERE tipo_dispositivo_id_tipo_dispositivo = ?");
        $stmt->bind_param("ii", $ip2,$id_tipo_dispositivo);
        return $stmt->execute();
    }
    public function getDispositivosDeTipo($ip2){
        $stmt = $this->conn->prepare("SELECT * FROM dispositivos WHERE tipo_dispositivo_ip2 = ?");
        $stmt->bind_param("i", $ip2);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function eliminarDispositivo($id_dispositivos){
        $stmt = $this->conn->prepare("DELETE FROM dispositivos WHERE id_dispositivos = ?");
        $stmt->bind_param("i", $id_dispositivos);
        return  $stmt->execute(); 
    }
    public function eliminarTipo($id_tipo_dispositivo){
        $stmt = $this->conn->prepare("DELETE FROM tipo_dispositivo WHERE id_tipo_dispositivo = ?");
        $stmt->bind_param("i", $id_tipo_dispositivo);
        return  $stmt->execute();
      
    }
    public function comprobarIP($ip2){
        $stmt = $this->conn->prepare("SELECT * from tipo_dispositivo where ip2 = ?");
        $stmt->bind_param("i", $ip2);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function comprobarTipo($equipo){
        $stmt = $this->conn->prepare("SELECT * from tipo_dispositivo where equipo = ?");
        $stmt->bind_param("s", $equipo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    /*
    public function localDeDispositivo($ip_local){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE ip3 = ?");
        $stmt->bind_param("i", $ip_local);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
        
    }






    public function eliminarDispositivosDeLocal($ip3){
        $stmt = $this->conn->prepare("DELETE  FROM dispositivos WHERE locales_ip3 = ?");
        $stmt->bind_param("i", $ip3);
        return  $stmt->execute();
    }
    //Funcion para oobtener las IDs de los usuarios de un correspondiente local

    //Eliminar todos los usuarios de un correspondiente local
    public function eliminarUsuarioDeLocal($denominacion){
        $stmt = $this->conn->prepare("DELETE FROM usuario_local WHERE denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        return  $stmt->execute();
    }
    //Eliminar los usuarios de la tabla usuarios,para eliminarlos en conjunto al local

    public function editarLocal($id_locales,$denominacion,$ciudad,$direccion,$ip3){
        $stmt = $this->conn->prepare("UPDATE locales SET  denominacion= ?, ciudad = ?, direccion = ?, ip3 = ?
        WHERE id_locales = ?");
        $stmt->bind_param("sssii", $denominacion, $ciudad, $direccion,$ip3,$id_locales);
        return $stmt->execute();
    }
    public function editarUsuarioLocal($id_locales,$denominacion){
        $stmt = $this->conn->prepare("UPDATE usuario_local SET  denominacion= ?
        WHERE locales_id_locales = ?");
        $stmt->bind_param("si", $denominacion,$id_locales);
        return $stmt->execute();
    }

    public function getDispositivosDeLocalCantidad($locales_ip3){
        $stmt = $this->conn->prepare("SELECT count(*) from dispositivos where locales_ip3 = ?");
        $stmt->bind_param("i", $locales_ip3);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getDispositivosDeLocal($locales_ip3){
        $stmt = $this->conn->prepare("SELECT * from dispositivos where locales_ip3 = ?");
        $stmt->bind_param("i", $locales_ip3);
        return $stmt->execute();
    }
*/


}
?>
