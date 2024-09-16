<?php
//require '../../config/conectar.php';
require 'model.php';


class UserController {
    private $model;
    private $conn;

    public function __construct($conn) {
        $this->model = new UserModel($conn);
        $this->conn = $conn;
    }

    public function logout() {
        // session_start();  // Inicia la sesión
        session_destroy(); // Destruye la sesión
        header("Location: ../../public/login.php"); // Redirige al index.php
        exit(); // Asegura que se detiene la ejecución
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['add_user'])) {
                $this->addUser($_POST['username'], $_POST['password'], $_POST['role']);
            } elseif (isset($_POST['edit_user'])) {
                $this->editUser($_POST['id'], $_POST['username'], $_POST['role']);
            }
        } elseif (isset($_GET['delete_user'])) {
            $this->deleteUser($_GET['delete_user']);
        } elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
            $this->logout(); // Llama al método logout
        }
    }

    private function addUser($username, $password, $role) {
        if (!empty($username) && !empty($password) && !empty($role)) {
            if ($this->model->addUser($username, $password, $role)) {
                $_SESSION['message'] = "Usuario agregado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al agregar usuario.";
            }
        } else {
            $_SESSION['error'] = "Por favor, complete todos los campos.";
        }
        header("Location: view.php");
    }


    private function editUser($id, $username, $role) {
        if (!empty($id) && !empty($username) && !empty($role)) {
            if ($this->model->updateUser($id, $username, $role)) {
                $_SESSION['message'] = "Usuario actualizado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar usuario.";
            }
        } else {
            $_SESSION['error'] = "Por favor, complete todos los campos.";
        }
        header("Location: view.php");
    }


    private function deleteUser($id) {
        if (!empty($id)) {
            if ($this->model->deleteUser($id)) {
                $_SESSION['message'] = "Usuario eliminado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al eliminar usuario.";
            }
        } else {
            $_SESSION['error'] = "ID de usuario inválido.";
        }
        header("Location: view.php");
    }

    public function getUserToEdit($id) {
        return $this->model->getUserById($id);
    }

    public function getAllUsers() {
        return $this->model->getAllUsers();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM usuarios WHERE nombre = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $userResult = $result->fetch_assoc();

        if ($userResult && password_verify($password, $userResult['contrasena'])) {
            session_start();
            $_SESSION['user'] = array(
                'id' => $userResult['id_usuarios'],
                //'userName' => $username,
                'nombre' => $userResult['nombre'],
                'rol' => $userResult['rol'],
            );
            return true;
        } else {
            return false;
        }
    }
}
?>
