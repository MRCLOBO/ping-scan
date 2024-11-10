<?php
class ModeloDispositivos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function mostrarDispositivos() {
        return $this->conn->query("SELECT * FROM dispositivos");
    }
    public function getLocales(){
        return $this->conn->query("SELECT * FROM locales");
    }
    public function getTipoDispositivos(){
        return $this->conn->query("SELECT * FROM tipo_dispositivo");
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
    public function getDispositivosDeTipo($tipo_dispositivo_ip2){
        $stmt = $this->conn->prepare("SELECT * from dispositivos where tipo_dispositivo_ip2 = ?");
        $stmt->bind_param("i", $tipo_dispositivo_ip2);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getDispositivosConFiltro($localesFiltro,$tipoDispositivosFiltro,$ordenFiltro){
        $consultaLocal = "";
        $consultaTipoDispositivos = "";
        $cantidadConsultaLocal= "";
        $cantidadConsultaTipoDispositivos="";

        if($localesFiltro !== false){
            $tamanoLocal = count($localesFiltro);
            //bucle para concatenar todos los locales para mostrar
            for($i1 = 0; $i1 < $tamanoLocal ; ++$i1 ){
                if($i1 == 0){
                    $consultaLocal = $localesFiltro[$i1];
                    $cantidadConsultaLocal = "locales_ip3 = ?";
                }else{
                    $consultaLocal = $consultaLocal.",". $localesFiltro[$i1];
                    $cantidadConsultaLocal = $cantidadConsultaLocal." OR locales_ip3 = ?";
                }
            }
        }
        if($tipoDispositivosFiltro !== false){
        $tamanoTipoDispositivo = count($tipoDispositivosFiltro);
            //bucle para concatenar a todos los tipos de dispositivo que se quieran mostrar
            for($i2 = 0; $i2 < $tamanoTipoDispositivo ; ++$i2 ){
                if($i2 == 0){
                    if($localesFiltro == false){
                    $consultaTipoDispositivos = $tipoDispositivosFiltro[$i2];
                    $cantidadConsultaTipoDispositivos = "tipo_dispositivo_ip2 = ?";
                    }else{
                     $consultaTipoDispositivos = $tipoDispositivosFiltro[$i2];
                        if($i2 == ($tamanoTipoDispositivo - 1)){
                        $cantidadConsultaTipoDispositivos = " AND tipo_dispositivo_ip2 = ?";      
                        }else{
                        $cantidadConsultaTipoDispositivos = " AND (tipo_dispositivo_ip2 = ?";  
                    }}
                }else{

                    //Dentro de la consulta intermedia
                    if($localesFiltro !== false && $i2 == ($tamanoTipoDispositivo - 1)){
                    $consultaTipoDispositivos = $consultaTipoDispositivos.",". $tipoDispositivosFiltro[$i2];
                    $cantidadConsultaTipoDispositivos = $cantidadConsultaTipoDispositivos." OR tipo_dispositivo_ip2 = ?)";

                    }else if($i2 == ($tamanoTipoDispositivo - 1) && $localesFiltro === false){
                    $consultaTipoDispositivos = $consultaTipoDispositivos.",". $tipoDispositivosFiltro[$i2];
                    $cantidadConsultaTipoDispositivos = $cantidadConsultaTipoDispositivos." OR tipo_dispositivo_ip2 = ?";

                    }else if($i2 !== ($tamanoTipoDispositivo - 1)){
                        $consultaTipoDispositivos = $consultaTipoDispositivos.",". $tipoDispositivosFiltro[$i2];
                        $cantidadConsultaTipoDispositivos = $cantidadConsultaTipoDispositivos." OR tipo_dispositivo_ip2 = ?";    
                    }
                }
            }
        }
            if($localesFiltro !== false && $tipoDispositivosFiltro !== false){
            $consultaCompleta = array_merge($localesFiltro,$tipoDispositivosFiltro);
            }else if($localesFiltro !== false && $tipoDispositivosFiltro === false){
                $consultaCompleta = $localesFiltro;
            }else if($localesFiltro === false && $tipoDispositivosFiltro !== false){
                $consultaCompleta = $tipoDispositivosFiltro;
            }

        if($localesFiltro !== false || $tipoDispositivosFiltro !== false){
            if($ordenFiltro == "ASC"){
            $stmt = $this->conn->prepare("SELECT * from dispositivos where ". $cantidadConsultaLocal.$cantidadConsultaTipoDispositivos." ORDER BY nombre_equipo ASC");
            } else if($ordenFiltro == "DESC"){
                $stmt = $this->conn->prepare("SELECT * from dispositivos where ". $cantidadConsultaLocal.$cantidadConsultaTipoDispositivos." ORDER BY nombre_equipo DESC");
            }else{
                $stmt = $this->conn->prepare("SELECT * from dispositivos where ".$cantidadConsultaLocal.$cantidadConsultaTipoDispositivos);
            }
            $_SESSION['notificacion']="Filtro aplicado";
            $stmt->execute($consultaCompleta);
        
        }else{
            if($ordenFiltro == "ASC"){
            $stmt = $this->conn->prepare("SELECT * from dispositivos ORDER BY nombre_equipo ASC");
            } else if($ordenFiltro == "DESC"){
                $stmt = $this->conn->prepare("SELECT * from dispositivos ORDER BY nombre_equipo DESC");
            }else{
                $stmt = $this->conn->prepare("SELECT * from dispositivos");
            }
            $_SESSION['notificacion']="Filtro aplicado";
            $stmt->execute();

        }
            //problema en el bind
            //$stmt->bind_param($bindLocal.$bindTipoDispositivos,$localesFiltro,$tipoDispositivosFiltro);

            return $stmt->get_result();
            
            
            
            /* Funciona
            $i32=32;
            $i63=63;
            $stmt = $this->conn->prepare("SELECT * from dispositivos where (locales_ip3 = ?) OR (tipo_dispositivo_ip2 = ?) ORDER BY nombre_equipo ASC");
            $stmt->bind_param("ii",$i32,$i63);
            $stmt->execute();
            return $stmt->get_result();
            */


    }
}
?>
