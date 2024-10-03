<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function añadirUsuario($usuario, $nombre, $rol, $contrasena) {
        $hashedContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, nombre, rol, contrasena) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $nombre, $rol,$hashedContrasena);
        return $stmt->execute();
       
    }

    public function updateUsuario($id_usuarios, $usuario, $nombre, $rol) {
        $stmt = $this->conn->prepare("UPDATE usuarios SET usuario = ?, nombre = ?, rol = ? WHERE id_usuarios = ?");
        $stmt->bind_param("sssi", $usuario, $nombre, $rol, $id_usuarios);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllUsers() {
        return $this->conn->query("SELECT * FROM usuarios");
    }
    public function getLocales() {
        return $this->conn->query("SELECT * FROM locales");
    }
    public function idPorDenominacion($denominacion){
        $stmt = $this->conn->prepare("SELECT * FROM locales WHERE denominacion = ?");
        $stmt->bind_param("s", $denominacion);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
        
    }
    public function idPorUsuario($usuario){
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function añadirUsuarioLocal($denominacion,$usuario){
        $pedirLocal = $this->idPorDenominacion($denominacion);
        $id_local= htmlspecialchars($pedirLocal['id_locales']);

        $pedirIDUsuario = $this->idPorUsuario($usuario);
        $id_usuarios= htmlspecialchars($pedirIDUsuario['id_usuarios']);

        $stmt = $this->conn->prepare("INSERT INTO usuario_local 
        (usuarios_id_usuarios,locales_id_locales,denominacion,usuario_nombre) 
        VALUES (?,?,?,?)");
         $stmt->bind_param("iiss", $id_usuarios,$id_local,$denominacion,$usuario);
         return $stmt->execute();
    }
    public function getUsuarioLocal($id_usuarios){
        $stmt = $this->conn->prepare("SELECT * FROM usuario_local WHERE usuarios_id_usuarios = ?");
        $stmt->bind_param("i", $id_usuarios);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editarUsuarioLocal($denominacion,$usuario){
        $pedirLocal = $this->idPorDenominacion($denominacion);
        $id_local= htmlspecialchars($pedirLocal['id_locales']);

        $pedirIDUsuario = $this->idPorUsuario($usuario);
        $id_usuarios= htmlspecialchars($pedirIDUsuario['id_usuarios']);

        //consultar si el usuario ya existe en la tabla usuario_local
        $consulta = $this->getUsuarioLocal($id_usuarios);
        if(htmlspecialchars($consulta['usuarios_id_usuarios']) >= 0 ){
            $stmt = $this->conn->prepare("UPDATE usuario_local SET locales_id_locales = ?, denominacion = ? WHERE usuarios_id_usuarios = ?");
            $stmt->bind_param("isi", $id_local, $denominacion, $id_usuarios);
            return $stmt->execute();
        }else{
            return $this->añadirUsuarioLocal($denominacion,$usuario);
        }
    }
    public function eliminarUsuarioLocal($usuario){
        $pedirIDUsuario = $this->idPorUsuario($usuario);
        $id_usuarios= htmlspecialchars($pedirIDUsuario['id_usuarios']);

        $stmt = $this->conn->prepare("DELETE FROM usuario_local WHERE usuarios_id_usuarios = ?");
        $stmt->bind_param("i", $id_usuarios);
        return $stmt->execute();
    }
}
?>
