<?php
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/fpdf182/fpdf.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/controlador_informe.php');

header('Content-Type: application/json');

// Obtener datos enviados desde el archivo JavaScript
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['dispositivos'])) {
    echo json_encode(['success' => false, 'message' => 'Datos de dispositivos no recibidos']);
    exit;
}

session_start();

// Crear la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();
$controlador = new InformeController($conn);

// Verificar si el usuario tiene un local asignado en sesión
$localId = $_SESSION['local'] ?? null;
$pedirLocal = /*$localId ?*/ $controlador->localDeDispositivo($localId) /*: []*/;
$dispositivosInfo = /*$localId ? */$controlador->getDispositivosDeLocal($localId) /*: []*/;

$fechaHoraActual = date('d/m/Y H:i:s');

$usuario = $_SESSION['usuario'] ;

$usuario=$usuario['usuario'];
// Reemplazar el estado de los dispositivos con el estado actual recibido desde JavaScript
foreach ($dispositivosInfo as &$dispositivo) {
    // Inicializar 'estado' como 'desconocido' por si no se encuentra en la lista de dispositivos de JavaScript
    $dispositivo['estado'] = 'desconocido';

    foreach ($data['dispositivos'] as $estadoDispositivo) {
        // Comparar con 'ip4' en lugar de 'id_dispositivo'
        if ($dispositivo['ip4'] == $estadoDispositivo['id']) {
            // Agregar el estado actual al dispositivo
            $dispositivo['estado'] = $estadoDispositivo['estado'];
            break;
        }
    }
}
$orientation="P";$unit="cm";$size="letter";
class PDF extends FPDF {
    private $local;
    private $dispositivos;
    private $fechaHoraActual;
    private $usuario;


    public function __construct($orientation, $unit, $size, $local, $dispositivos, $fechaHoraActual, $usuario) {
        parent::__construct($orientation, $unit, $size);
        $this->local = $local;
        $this->dispositivos = $dispositivos;
        $this->fechaHoraActual = $fechaHoraActual;
        $this->usuario = $usuario;
    }

    function Header() {
        $this->SetFont("Arial", "", 24);
        $this->Image("somelogo.png", 1, 1); // Ajusta la ruta de la imagen
        $this->Cell(9);
        $this->Cell(10, 2, utf8_decode("Informe PingScan"), 1, 1, 'C');
        $this->SetFont("Arial", "", 16);
        $this->Cell(5);
        $this->SetTextColor(12, 22, 104); // Azul marino
        $this->Cell(10, 2, utf8_decode("Estado de conexión de los equipos"), 0, 0, 'C');
    }

    function Body() {
        $this->SetFont("Arial", 'B', 14);
        $this->Ln();
        $this->SetTextColor(62, 72, 204); 
        $this->Cell(0, 1, utf8_decode("Información del Local"), 0, 1, 'C');
        $this->SetFont("Arial", '', 12);
        $this->SetTextColor(0, 0, 0);

        foreach ($this->local as $localData) {
            $this->Cell(6, 1, "Local ID: " . $localData['id_locales'], 0, 0);
            $this->Cell(0, 1, "Fecha y Hora: " . $this->fechaHoraActual, 0, 1, 'R');
            $this->Cell(6, 1, "Nombre: " . utf8_decode($localData['denominacion']), 0, 0);
            $this->Cell(0, 1, "Usuario: " . $this->usuario, 0, 1,'R');
            $this->Cell(6, 1, utf8_decode("Dirección: ") . utf8_decode($localData['direccion']), 0, 1);
            $this->Cell(6, 1, "Ciudad: " . utf8_decode($localData['ciudad']), 0, 0);
            
            $this->Ln();
        }
        
        /*$this->Cell(6, 1, "Usuario: " . $this->usuario, 0, 1);
        $this->Ln();*/
        

        $this->SetFont("Arial", 'B', 14);
        $this->SetTextColor(62, 72, 204);
        $this->Cell(0, 1, "Dispositivos", 0, 1, 'C');
        $this->SetFont("Arial", '', 12);
        $this->SetTextColor(0, 0, 0);

        // Encabezados de los dispositivos
        $this->Cell(3, 1, "ID", 1, 0, 'C');
        $this->Cell(6, 1, "Nombre", 1, 0, 'C');
        $this->Cell(5, 1, "IP", 1, 0, 'C');
        $this->Cell(5, 1, "Estado", 1, 1, 'C');

        foreach ($this->dispositivos as $dispositivo) {
            $this->Cell(3, 1, $dispositivo['id_dispositivos'], 1, 0, 'C');
            $this->Cell(6, 1, utf8_decode($dispositivo['nombre_equipo']), 1, 0, 'C');
            $this->Cell(5, 1, utf8_decode($dispositivo['ip1'].'.'.$dispositivo['tipo_dispositivo_ip2'].'.'.$dispositivo['locales_ip3'].'.'.$dispositivo['ip4']), 1, 0, 'C');//10.63.32.14
            $this->Cell(5, 1, utf8_decode($dispositivo['estado']), 1, 1, 'C');
        }
    }

    function Footer() {
        $this->SetY(-2);
        $this->SetFont("Arial", 'I', 10);
        $this->Cell(0, 1, "Fin del Informe", 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'cm', 'letter', $pedirLocal, $dispositivosInfo, $fechaHoraActual, $usuario);
$pdf->SetAuthor("PingScan", true);
$pdf->SetTitle("Informe de Local y Dispositivos", true);
$pdf->AddPage();
$pdf->Body();

// Guardar y enviar el PDF
$fechaHora = date('Y-m-d_H-i-s');
$rutaCarpeta = $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/informes1/';
/*if (!file_exists($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
}*/
//$rutaArchivo = $rutaCarpeta . 'Informe_Dispositivos.pdf';
$nombreArchivo = 'Informe_Dispositivos_' . $fechaHora . '.pdf';
$rutaArchivo = $rutaCarpeta . $nombreArchivo;
$pdf->Output($rutaArchivo, 'F');

// Crear la URL pública
//$urlArchivo = 'http://localhost/ping-scan/modules/Administrador/informes/informes1/Informe_Dispositivos.pdf';
$urlArchivo = 'http://localhost/ping-scan/modules/Administrador/informes/informes1/' . $nombreArchivo;

// Responder con la URL pública del archivo PDF en formato JSON
echo json_encode(['success' => true, 'filePath' => $urlArchivo]);



// Guardar y enviar el PDF



/*$rutaCarpeta = $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/';
if (!file_exists($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
}
$rutaArchivo = $rutaCarpeta . 'Informe_Dispositivos.pdf';
$pdf->Output($rutaArchivo, 'F');

// Responder con la ruta del archivo PDF en formato JSON
echo json_encode(['success' => true, 'filePath' => $rutaArchivo]);*/
?>
