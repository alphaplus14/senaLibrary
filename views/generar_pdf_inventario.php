<?php
// Archivo: views/generar_pdf_inventario.php
// Descripción: Genera un PDF con el inventario actual de la biblioteca + el libro más prestado.

declare(strict_types=1);

require_once __DIR__ . '/../libs/fpdf/fpdf.php';

// === Conexión a la base de datos ===
function crearConexionPdo(): PDO {
    $host = 'localhost';
    $nombreBaseDatos = 'SenaLibrary';
    $usuario = 'root';
    $clave = '';

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

// === Obtener libro más prestado ===
function obtenerLibroMasPrestado(PDO $conexion): ?array {
    $sql = "SELECT 
                l.id_libro AS ID,
                l.titulo_libro AS Titulo,
                l.autor_libro AS Autor,
                l.categoria_libro AS Categoria,
                l.cantidad_libro AS Cantidad,
                COUNT(rhl.libro_id_libro) AS VecesPrestado
            FROM reserva_has_libro rhl
            JOIN libro l ON l.id_libro = rhl.libro_id_libro
            GROUP BY rhl.libro_id_libro
            ORDER BY VecesPrestado DESC
            LIMIT 1";
    $consulta = $conexion->query($sql);
    $resultado = $consulta->fetch();
    return $resultado ?: null;
}

// === Generar PDF ===
try {
    $conexion = crearConexionPdo();
    $items = obtenerInventario($conexion);
    $libroMasPrestado = obtenerLibroMasPrestado($conexion);

    $pdf = new PDFInventario('L'); // L = horizontal
    $pdf->AddPage();

    // === Tabla de inventario ===
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(20, 9, 'ID', 1, 0, 'C', true);
    $pdf->Cell(70, 9, 'Titulo', 1, 0, 'C', true);
    $pdf->Cell(60, 9, 'Autor', 1, 0, 'C', true);
    $pdf->Cell(45, 9, 'Categoria', 1, 0, 'C', true);
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

// === Sección del libro más prestado ===
// === Sección del libro más prestado ===
$pdf->Ln(4); // pequeño margen entre tablas
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('* Libro más prestado'), 0, 1, 'L');
$pdf->Ln(2); // espacio sutil antes de la tabla

if ($libroMasPrestado) {
// Encabezado de la tabla (mismos anchos que la tabla principal)
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(200, 230, 255);
$pdf->Cell(20, 9, utf8_decode('ID'), 1, 0, 'C', true);
$pdf->Cell(70, 9, utf8_decode('Titulo'), 1, 0, 'C', true);
$pdf->Cell(60, 9, utf8_decode('Autor'), 1, 0, 'C', true);
$pdf->Cell(45, 9, utf8_decode('Categoria'), 1, 0, 'C', true);
$pdf->Cell(25, 9, utf8_decode('Cant.'), 1, 0, 'C', true);
$pdf->Cell(40, 9, utf8_decode('Veces Prestado'), 1, 1, 'C', true);

// Datos del libro más prestado
// Datos del libro más prestado
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 8, $libroMasPrestado['ID'] ?? '-', 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($libroMasPrestado['Titulo'] ?? '-'), 1);
$pdf->Cell(60, 8, utf8_decode($libroMasPrestado['Autor'] ?? '-'), 1);
$pdf->Cell(45, 8, utf8_decode($libroMasPrestado['Categoria'] ?? '-'), 1);
$pdf->Cell(25, 8, $libroMasPrestado['Cantidad'] ?? '-', 1, 0, 'C');
$pdf->Cell(40, 8, $libroMasPrestado['VecesPrestado'] ?? '-', 1, 1, 'C');

} else {
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 8, utf8_decode('No hay registros de préstamos aún.'), 1, 1, 'C');
}

    // === Salida del PDF ===
    $salida = $_GET['salida'] ?? 'I'; // I = ver, D = descargar
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
?>


