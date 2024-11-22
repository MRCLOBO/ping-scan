<?php
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/fpdf182/fpdf.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/config/conectar.php');
require($_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/controlador_informe.php');

// Crear la conexión
$conexion = new Conectar();
$conn = $conexion->getConexion();

// Crear instancia del modelo
$informeModel = new InformeModel($conn);

// Obtener los reportes (usuarios en este caso)
$reportes = $informeModel->getAllUsers(); // Obtener los usuarios como un array asociativo



class PDF extends FPDF {
    private $reportes; // Añadimos una propiedad para los reportes

    // Constructor que recibe los datos
    public function __construct($orientation='P', $unit='cm', $size='letter', $reportes) {
        parent::__construct($orientation, $unit, $size);
        $this->reportes = $reportes; // Guardamos los reportes en la propiedad
    }

    function Header() {
        $this->SetFont("Arial", "", 24);
        $this->Image("somelogo.png", 1, 1); // Ajusta la ruta de la imagen
        $this->Cell(9);
        $this->Cell(10, 2, utf8_decode("Informe PingScan"), 1, 1, 'C');
        $this->Cell(5);
        $this->SetFont("Arial", "", 16);
        $this->SetTextColor(12, 22, 104); // Azul marino
        $this->Cell(10, 2, utf8_decode("Datos de Usuarios"), 0, 0, 'C');
        //$this->Ln(1); // Salto de línea
    }

    function Body() {
        $this->SetFont("Arial", 'B', 14);
        $this->Ln();
        $this->SetTextColor(62, 72, 204); // Azul marino
        $this->Cell(4, 1, "ID", 1, 0, 'C');
        $this->Cell(6, 1, "Nombre de Usuario", 1, 0, 'C');
        $this->Cell(6, 1, "Usuario", 1, 0, 'C');
        $this->Cell(3, 1, "Rol", 1, 1, 'C');
        $this->SetFont("Arial", '', 12);
        $this->SetTextColor(0, 0, 0);

        // Imprimir datos de los usuarios
       foreach ($this->reportes as $reporte) {
            $this->Cell(4, 1, $reporte['id_usuarios'], 1, 0, 'C');
            $this->Cell(6, 1, utf8_decode($reporte['nombre']), 1, 0, 'C');
            $this->Cell(6, 1, utf8_decode($reporte['usuario']), 1, 0, 'C');
            $this->Cell(3, 1, utf8_decode($reporte['rol']), 1, 1, 'C');
        }
    }

    function Footer() {
        $this->SetY(-2);
        $this->SetFont("Arial", 'I', 10);
        $this->Cell(0, 1, "Fin del Informe", 0, 0, 'C');
    }
}


// Crear el PDF pasando los reportes
$pdf = new PDF('P', 'cm', 'letter', $reportes);
$pdf->SetAuthor("PingScan", true);
$pdf->SetTitle("Informe de Usuarios", true);
$pdf->AddPage();
$pdf->Body();

$fechaHora = date('Y-m-d_H-i-s');
$rutaCarpeta = $_SERVER['DOCUMENT_ROOT'].'/ping-scan/modules/Administrador/informes/informeUsuario/';
$nombreArchivo = 'Informe_usuarios_' . $fechaHora . '.pdf';
$rutaArchivo = $rutaCarpeta . $nombreArchivo;
$pdf->Output($rutaArchivo, 'F');

$pdf->Output(); // Envía el archivo al navegador para su descarga/visualización
$pdf->Output($rutaArchivo, 'F');
//$pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/ping-scan/modules/Administrador/informes/Informe_Usuarios.pdf', 'F');

//$pdf->Output("Informe_Usuarios.pdf", 'F'); // Guarda el archivo en el servidor

?>