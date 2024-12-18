<?php
class ModeloLocales {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getLocales() {
        return $this->conn->query("SELECT * FROM locales");
    }
    public function localDeDispositivo($ip_local){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE ip3 = ?");
        $stmt->bind_param("i", $ip_local);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
        
    }

    public function añadirLocal($denominacion,$ciudad,$direccion,$ip3){
        $stmt = $this->conn->prepare("INSERT INTO locales(denominacion,ciudad,direccion,ip3) VALUES(? ,? ,? ,?)");
        $stmt->bind_param("sssi", $denominacion,$ciudad,$direccion,$ip3);
        $stmt->execute();
        
    }

    public function getLocalID($id_locales){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE id_locales = ?");
        $stmt->bind_param("i", $id_locales);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function eliminarLocal($id_locales){
        $stmt = $this->conn->prepare("DELETE FROM locales WHERE id_locales = ?");
        $stmt->bind_param("i", $id_locales);
        return  $stmt->execute();
      
    }
    public function eliminarDispositivosDeLocal($ip3){
        $stmt = $this->conn->prepare("DELETE  FROM dispositivos WHERE locales_ip3 = ?");
        $stmt->bind_param("i", $ip3);
        return  $stmt->execute();
    }
    //Funcion para oobtener las IDs de los usuarios de un correspondiente local
    public function getUsuariosDelLocal($denominacion){
        $stmt = $this->conn->prepare("SELECT * FROM usuario_local WHERE denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        $stmt->execute();
        return $stmt->get_result();
    }
    //Eliminar todos los usuarios de un correspondiente local
    public function eliminarUsuarioDeLocal($denominacion){
        $stmt = $this->conn->prepare("DELETE FROM usuario_local WHERE denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        return  $stmt->execute();
    }
    //Eliminar los usuarios de la tabla usuarios,para eliminarlos en conjunto al local
    public function eliminarUsuario($id_usuarios){
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("i", $id_usuarios);
        return  $stmt->execute(); 
    }
    public function editarUsuario($id_locales,$denominacion,$ciudad,$direccion,$ip3){
        $stmt = $this->conn->prepare("UPDATE locales SET  denominacion= ?, ciudad = ?, direccion = ?, ip3 = ?
        WHERE id_locales = ?");
        $stmt->bind_param("sssii", $denominacion, $ciudad, $direccion,$ip3,$id_locales);
        return $stmt->execute();
    }
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
    public function editarLocalDeDispositivos($id_locales,$ip3){
        $stmt = $this->conn->prepare("UPDATE dispositivos SET  locales_ip3= ?
        WHERE locales_id_locales = ?");
        $stmt->bind_param("ii", $ip3,$id_locales);
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
    public function comprobarIP($ip3){
        $stmt = $this->conn->prepare("SELECT * from locales where ip3 = ?");
        $stmt->bind_param("i", $ip3);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function comprobarDenominacion($denominacion){
        $stmt = $this->conn->prepare("SELECT * from locales where denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
?>
