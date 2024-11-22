<?php
//require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php';

class InformeModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Retornar todos los usuarios como un array asociativo
    }

    public function localDeDispositivo($ip_local){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE ip3 = ?");
        $stmt->bind_param("i", $ip_local);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDispositivosDeLocal($locales_ip3) {
        $stmt = $this->conn->prepare("SELECT * FROM dispositivos WHERE locales_ip3 = ?");
        $stmt->bind_param("i", $locales_ip3);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Convertimos el resultado en un array asociativo
    }
}

/*class InformeModel1 {
    private $conn;

    public function __construct($conn) {
        
        $this->conn = $conn;
    }

    public function obtenerReportes($fechaInicio, $fechaFin, $usuarioId, $localId = null) {
        $query = "SELECT * FROM reportes WHERE fecha BETWEEN ? AND ? AND usuarios_id_usuarios = ?";
        
        if ($localId) {
            $query .= " AND locales_id_locales = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssii", $fechaInicio, $fechaFin, $usuarioId, $localId);
        } else {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $fechaInicio, $fechaFin, $usuarioId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAllUsers() {
        return $this->conn->query("SELECT * FROM usuarios");
    }
}*/
?>
