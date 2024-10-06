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


    public function getLocalID($id_locales){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE id_locales = ?");
        $stmt->bind_param("i", $id_locales);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function eliminarDispositivo($id_dispositivos){
        $stmt = $this->conn->prepare("DELETE FROM dispositivos WHERE id_dispositivos = ?");
        $stmt->bind_param("i", $id_dispositivos);
        return  $stmt->execute();
      
    }
}
?>
