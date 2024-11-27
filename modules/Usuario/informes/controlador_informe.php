<?php
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/modelo_informe.php');


class InformeController {
    private $model;
    private $conn;


    public function __construct($conn) {
        $this->model = new InformeModel($conn);
        $this->conn = $conn;
    }

    /*public function generarInforme($fechaInicio, $fechaFin, $usuarioId, $localId = null) {
        return $this->model->obtenerReportes($fechaInicio, $fechaFin, $usuarioId, $localId);
    }*/

    public function getAllUsers() {
        return $this->model->getAllUsers();
    }

    public function localDeDispositivo($ip_local){
        return $this->model->localDeDispositivo($ip_local);
    }

    public function getDispositivosDeLocal($locales_ip3){
        return $this->model->getDispositivosDeLocal($locales_ip3);
    }
}

// Ejemplo de uso
/*session_start();


$usuarioId = $_SESSION['id_usuarios'];
$localId = $_SESSION['local_id']; // Asegúrate de tener esto en la sesión si es necesario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];

    // Si es admin, puedes recibir un local_id
    if ($_SESSION['rol'] == 'admin') {
        $localId = $_POST['local_id'] ?? null; // Puedes definir cómo obtener el local_id
    }

    $reportes = $controller->generarInforme($fechaInicio, $fechaFin, $usuarioId, $localId);

    // Aquí puedes pasar $reportes a la vista que genere el PDF
}*/
?>
