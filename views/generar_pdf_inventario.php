<?php
// Archivo: views/generar_pdf_inventario.php
// Descripción: Genera un PDF con el inventario actual de la biblioteca.

declare(strict_types=1);

require_once __DIR__ . '/../libs/fpdf/fpdf.php';

// === Conexión a la base de datos ===
function crearConexionPdo(): PDO {
    $host = 'localhost';
    $nombreBaseDatos = 'SenaLibrary'; // nombre de la base de datos
    $usuario = 'root';
    $clave = ''; // Ajusta si tu MySQL tiene contraseña

    $dsn = "mysql:host={$host};dbname={$nombreBaseDatos};charset=utf8mb4";
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO($dsn, $usuario, $clave, $opciones);
}

// === Clase PDF personalizada ===
class PDFInventario extends FPDF {
    public function Header(): void {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('Inventario Actual - SenaLibrary'), 0, 1, 'C');
        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 6, 'Fecha del reporte: ' . date('d/m/Y'), 0, 1, 'R');
        $this->Ln(5);
    }

    public function Footer(): void {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// === Obtener inventario ===
function obtenerInventario(PDO $conexion): array {
    $sql = "SELECT 
                id_libro AS ID, 
                titulo_libro AS Titulo, 
                autor_libro AS Autor, 
                categoria_libro AS Categoria,
                cantidad_libro AS Cantidad, 
                disponibilidad_libro AS Disponibilidad
            FROM libro
            ORDER BY titulo_libro ASC";
    $consulta = $conexion->query($sql);
    return $consulta->fetchAll();
}

// === Generar PDF ===
try {
    $conexion = crearConexionPdo();
    $items = obtenerInventario($conexion);

    $pdf = new PDFInventario('L'); // L = horizontal
    $pdf->AddPage();

    // Encabezado tabla
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(20, 9, 'ID', 1, 0, 'C', true);
    $pdf->Cell(70, 9, 'Título', 1, 0, 'C', true);
    $pdf->Cell(60, 9, 'Autor', 1, 0, 'C', true);
    $pdf->Cell(45, 9, 'Categoría', 1, 0, 'C', true);
    $pdf->Cell(25, 9, 'Cant.', 1, 0, 'C', true);
    $pdf->Cell(40, 9, 'Disponibilidad', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    if (empty($items)) {
        $pdf->Cell(260, 9, 'No hay registros en el inventario.', 1, 1, 'C');
    } else {
        foreach ($items as $item) {
            $pdf->Cell(20, 8, $item['ID'], 1);
            $pdf->Cell(70, 8, utf8_decode($item['Titulo']), 1);
            $pdf->Cell(60, 8, utf8_decode($item['Autor']), 1);
            $pdf->Cell(45, 8, utf8_decode($item['Categoria']), 1);
            $pdf->Cell(25, 8, $item['Cantidad'], 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($item['Disponibilidad']), 1, 1, 'C');
        }
    }

 $salida = $_GET['salida'] ?? 'I'; // Por defecto, mostrar en el navegador
$nombreArchivo = 'Inventario_Actual_' . date('Y-m-d') . '.pdf';

if (ob_get_length()) ob_end_clean();
$pdf->Output($salida, $nombreArchivo);
exit;

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Error al generar el PDF de inventario: ' . $e->getMessage();
    exit;
}

