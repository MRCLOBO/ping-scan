<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addUser($username, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $role);
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

}
?>
