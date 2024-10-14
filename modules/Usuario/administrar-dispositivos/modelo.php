<?php
class ModeloDispositivos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function mostrarDispositivos() {
        return $this->conn->query("SELECT * FROM dispositivos");
    }

    public function localDeDispositivo($ip_local){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE ip3 = ?");
        $stmt->bind_param("i", $ip_local);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
        
    }
    public function tipoDispositivo($ip_tipo_dispositivo){
        $stmt = $this->conn->prepare("SELECT * FROM tipo_dispositivo WHERE ip2 = ?");
        $stmt->bind_param("i", $ip_tipo_dispositivo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function añadirDispositivo($ip1,$ip2,$ip3,$ip4,$nombre_equipo){
        $pedirLocal = $this->localDeDispositivo($ip3);
        $id_local= htmlspecialchars($pedirLocal['id_locales']);
        
        $pedirTipo = $this->tipoDispositivo($ip2);
        $id_tipo= htmlspecialchars($pedirTipo['id_tipo_dispositivo']);

        $stmt = $this->conn->prepare("INSERT INTO dispositivos 
        (ip1,tipo_dispositivo_ip2,locales_ip3,ip4,locales_id_locales,tipo_dispositivo_id_tipo_dispositivo,nombre_equipo) 
        VALUES (?,?,?,?,?,?,?)");
        /*NOTA
        Al ejecutar bind_param() el primer argumento especifica que tipo de 
        valor se mandara para cada valor seguido de manera secuencial y acepta los valores:
        i = integer
        s = string
        b = boolean
        d = decimal
        Si el primer valor a pasar es un booleano,seria: bind_param("b", $valorEjemplo);        
        */ 
        $stmt->bind_param("iiiiiis", $ip1,$ip2,$ip3,$ip4,$id_local,$id_tipo,$nombre_equipo);
        return $stmt->execute();

    }

    public function getDispositivoID($id_dispositivos){
        $stmt = $this->conn->prepare("SELECT * FROM dispositivos WHERE id_dispositivos = ?");
        $stmt->bind_param("i", $id_dispositivos);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function updateDispositivo($id_dispositivos,$ip1,$ip2,$ip3,$ip4,$nombre_equipo){
        $pedirLocal = $this->localDeDispositivo($ip3);
        $id_local= htmlspecialchars($pedirLocal['id_locales']);
        
        $pedirTipo = $this->tipoDispositivo($ip2);
        $id_tipo= htmlspecialchars($pedirTipo['id_tipo_dispositivo']);

        $stmt = $this->conn->prepare("UPDATE dispositivos SET  ip1= ?, tipo_dispositivo_ip2 = ?, locales_ip3 = ?, ip4 = ?,
        tipo_dispositivo_id_tipo_dispositivo = ?, locales_id_locales = ?, nombre_equipo = ? WHERE id_dispositivos = ?");
        $stmt->bind_param("iiiiiisi", $ip1, $ip2, $ip3,$ip4,$id_tipo,$id_local,$nombre_equipo,$id_dispositivos);
        return $stmt->execute();
    }
    public function eliminarDispositivo($id_dispositivos){
        $stmt = $this->conn->prepare("DELETE FROM dispositivos WHERE id_dispositivos = ?");
        $stmt->bind_param("i", $id_dispositivos);
        return  $stmt->execute();
      
    }
    public function getDispositivosDeLocal($locales_ip3){
        $stmt = $this->conn->prepare("SELECT * from dispositivos where locales_ip3 = ?");
        $stmt->bind_param("i", $locales_ip3);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getUsuarioLocal($id_usuarios){
        $stmt = $this->conn->prepare("SELECT * FROM usuario_local WHERE usuarios_id_usuarios = ?");
        $stmt->bind_param("i", $id_usuarios);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getLocal($denominacion){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
