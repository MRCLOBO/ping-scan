<?php
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/fpdf182/fpdf.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/controlador_informe.php');

// Crear la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Instanciar el controlador
$controlador = new InformeController($conn);

// Iniciar sesión
session_start();

// Verificar si el usuario tiene un local asignado en sesión
$localId = $_SESSION['local'] ?? null;
$pedirLocal = $localId ? $controlador->localDeDispositivo($localId) : [];
$dispositivos = $localId ? $controlador->getDispositivosDeLocal($localId) : [];

$usuario = $_SESSION['usuario'] ;
echo '<pre>';
print_r($usuario['usuario']);
echo '</pre>';

/*echo '<pre>';
print_r($dispositivos);
echo '</pre>';*/

/*echo '<pre>';
print_r($_SESSION);
echo '</pre>';*/

class PDF extends FPDF {
    private $local;
    private $dispositivos;

    // Constructor que recibe los datos
    public function __construct($orientation='P', $unit='cm', $size='letter', $local, $dispositivos) {
        parent::__construct($orientation, $unit, $size);
        $this->local = $local;
        $this->dispositivos = $dispositivos;
    }

    function Header() {
        $this->SetFont("Arial", "", 24);
        $this->Image("somelogo.png", 1, 1); // Ajusta la ruta de la imagen
        $this->Cell(9);
        $this->Cell(10, 2, utf8_decode("Informe PingScan"), 1, 1, 'C');
        $this->Cell(5);
        $this->SetFont("Arial", "", 16);
        $this->SetTextColor(12, 22, 104); // Azul marino
        $this->Cell(10, 2, utf8_decode("Estado de conexión de los equipos"), 0, 0, 'C');
    }

    function Body() {
        // Información del Local
        $this->SetFont("Arial", 'B', 14);
        $this->Ln();
        $this->SetTextColor(62, 72, 204); 
        $this->Cell(0, 1, "Información del Local", 0, 1, 'C');
        $this->SetFont("Arial", '', 12);
        $this->SetTextColor(0, 0, 0);

        foreach ($this->local as $localData) {
            $this->Cell(6, 1, "Local ID: " . $localData['id_locales'], 0, 1);
            $this->Cell(6, 1, "Nombre: " . utf8_decode($localData['denominacion']), 0, 1);
            $this->Cell(6, 1, "Dirección: " . utf8_decode($localData['direccion']), 0, 1);
            $this->Cell(6, 1, "Ciudad: " . utf8_decode($localData['ciudad']), 0, 1);
            $this->Ln();
        }

        // Información de Dispositivos
        $this->SetFont("Arial", 'B', 14);
        $this->SetTextColor(62, 72, 204);
        $this->Cell(0, 1, "Dispositivos", 0, 1, 'C');
        $this->SetFont("Arial", '', 12);
        $this->SetTextColor(0, 0, 0);

        // Encabezados de los dispositivos
        $this->Cell(4, 1, "ID", 1, 0, 'C');
        $this->Cell(6, 1, "Nombre", 1, 0, 'C');
        $this->Cell(6, 1, "IP", 1, 0, 'C');
        $this->Cell(3, 1, "Estado", 1, 1, 'C');

        foreach ($this->dispositivos as $dispositivo) {
            $this->Cell(4, 1, $dispositivo['id_dispositivo'], 1, 0, 'C');
            $this->Cell(6, 1, utf8_decode($dispositivo['nombre_equipo']), 1, 0, 'C');
            $this->Cell(6, 1, utf8_decode($dispositivo['ip']), 1, 0, 'C');
            $this->Cell(3, 1, utf8_decode($dispositivo['estado']), 1, 1, 'C');
        }
    }

    function Footer() {
        $this->SetY(-2);
        $this->SetFont("Arial", 'I', 10);
        $this->Cell(0, 1, "Fin del Informe", 0, 0, 'C');
    }
}

// Crear el PDF pasando los datos del local y los dispositivos
$pdf = new PDF('P', 'cm', 'letter', $pedirLocal, $dispositivos);
$pdf->SetAuthor("PingScan", true);
$pdf->SetTitle("Informe de Local y Dispositivos", true);
$pdf->AddPage();
$pdf->Body();
$pdf->Output(); // Envía el archivo al navegador para su descarga/visualización
$pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/ping-scan/modules/Administrador/informes/Informe_Usuarios.pdf', 'F'); // Guarda el archivo en el servidor

?>
