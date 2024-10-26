<?php
//require '../../config/conectar.php';
require $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/seguridad_autenticacion/model.php';


class ControladorUsuarios {
    private $model;
    private $conn;

    public function __construct($conn) {
        $this->model = new UserModel($conn);
        $this->conn = $conn;
    }

    public function logout() {
        // session_start();  // Inicia la sesión
        session_destroy(); // Destruye la sesión
        header("Location: ../../../public/login.php"); // Redirige al index.php
        exit(); // Asegura que se detiene la ejecución
    }
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['add_user'])) {
                $this->addUser($_POST['usuario'], $_POST['contrasena'], $_POST['rol']);
            } elseif (isset($_POST['edit_user'])) {
                $this->editUser($_POST['id_usuarios'], $_POST['usuario'], $_POST['rol']);
            }
        } elseif (isset($_GET['delete_user'])) {
            $this->deleteUser($_GET['delete_user']);
        } elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
            $this->logout(); // Llama al método logout
        }
    }

    public function añadirUsuario($usuario, $nombre, $rol,$contrasena) {
        if (!empty($usuario) && !empty($nombre) && !empty($rol) && !empty($contrasena)) {
            if ($this->model->añadirUsuario($usuario, $nombre, $rol, $contrasena)) {
                $_SESSION['notificacion'] = "Usuario agregado exitosamente.";
            } else {
                $_SESSION['notificacion'] = "Error al agregar usuario.";
            }
        } else {
            $_SESSION['notificacion'] = "Por favor, complete todos los campos.";
        }
        header("Location: vista.php");
    }
    public function añadirUsuarioLocal($denominacion,$usuario){
        return $this->model->añadirUsuarioLocal($denominacion,$usuario);
    }
    
    public function editarUsuario($id_usuarios, $usuario, $nombre, $rol) {
        if (!empty($id_usuarios) && !empty($usuario) && !empty($nombre) && !empty($rol)) {
            
            if ($this->model->updateUsuario($id_usuarios, $usuario, $nombre,$rol)) {
                $_SESSION['message'] = "Usuario actualizado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar usuario.";
            }
        } else {
            $_SESSION['error'] = "Por favor, complete todos los campos.";
        }
        header("Location: vista.php");
    }


    public function eliminarUsuario($id) {
        session_start();
        if (!empty($id)) {
            if ($this->model->eliminarUsuario($id)) {
                $_SESSION['notificacion'] = "Usuario eliminado exitosamente.";
            } else {
                $_SESSION['notificacion'] = "Error al eliminar usuario.";
            }
        } else {
            $_SESSION['notificacion'] = "ID de usuario inválido.";
        }
        header("Location: vista.php");
        die();
    }

    public function getUserToEdit($id) {
        return $this->model->getUserById($id);
    }

    public function getAllUsers() {
        return $this->model->getAllUsers();
    }
    public function getLocales(){
        return $this->model->getLocales();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $userResult = $result->fetch_assoc();

        if ($userResult && password_verify($password, $userResult['contrasena'])) {
            session_start();
            $_SESSION['usuario'] = array(
                'id' => $userResult['id_usuarios'],
                //'userName' => $username,
                'usuario' => $userResult['usuario'],
                'rol' => $userResult['rol'],
            );
            $_SESSION['local']=null;
            $_SESSION['rol']= $userResult['rol'];
            $_SESSION['id_usuarios']=$userResult['id_usuarios'];
            return true;
        } else {
            return false;
        }
    }
    public function getUsuarioLocal($id_usuarios){
        return $this->model->getUsuarioLocal($id_usuarios);
    }
    public function editarUsuarioLocal($denominacion,$usuario){
        return $this->model->editarUsuarioLocal($denominacion,$usuario);
    }
    public function eliminarUsuarioLocal($usuario){
        return $this->model->eliminarUsuarioLocal($usuario);
    }
    public function restaurarContrasena($id_usuarios){
        return $this->model->restaurarContrasena($id_usuarios);
    }

}
?>
