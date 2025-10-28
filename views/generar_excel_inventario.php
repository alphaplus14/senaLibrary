<?php
// Archivo: views/generar_excel_inventario.php
// Descripción: Genera un archivo Excel con el inventario actual de la biblioteca.

declare(strict_types=1);

// === Librerías necesarias ===
require_once __DIR__ . '/../vendor/autoload.php'; // asegúrate de tener phpoffice/phpspreadsheet
require_once __DIR__ . '/../models/MySQL.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

try {
    // === Conexión a la base de datos ===
 // === Conexión a la base de datos ===
$host = 'localhost';
$nombreBaseDatos = 'SenaLibrary';
$usuario = 'root';
$clave = '';

$dsn = "mysql:host={$host};dbname={$nombreBaseDatos};charset=utf8mb4";
$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$conexion = new PDO($dsn, $usuario, $clave, $opciones);

$query = "SELECT 
            id_libro AS ID, 
            titulo_libro AS Título, 
            autor_libro AS Autor, 
            categoria_libro AS Categoría, 
            cantidad_libro AS Cantidad, 
            disponibilidad_libro AS Disponibilidad
          FROM libro
          ORDER BY titulo_libro ASC";
$stmt = $conexion->query($query);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // === Crear el documento Excel ===
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Inventario Actual');

    // === Encabezado ===
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Inventario Actual - SenaLibrary');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y'));
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->mergeCells('A2:F2');

    // === Encabezados de tabla ===
    $headers = ['ID', 'Título', 'Autor', 'Categoría', 'Cantidad', 'Disponibilidad'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '4', $header);
        $sheet->getStyle($col . '4')->getFont()->setBold(true);
        $sheet->getStyle($col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');
        $col++;
    }

    // === Contenido ===
    $fila = 5;
    foreach ($libros as $libro) {
        $sheet->setCellValue('A' . $fila, $libro['ID']);
        $sheet->setCellValue('B' . $fila, $libro['Título']);
        $sheet->setCellValue('C' . $fila, $libro['Autor']);
        $sheet->setCellValue('D' . $fila, $libro['Categoría']);
        $sheet->setCellValue('E' . $fila, $libro['Cantidad']);
        $sheet->setCellValue('F' . $fila, $libro['Disponibilidad']);
        $fila++;
    }

    // === Ajustar anchos de columna automáticamente ===
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // === Aplicar bordes ===
    $sheet->getStyle('A4:F' . ($fila - 1))
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // === Salida del archivo Excel ===
    $nombreArchivo = 'Inventario_Actual_' . date('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo "Error al generar el Excel del inventario: " . $e->getMessage();
    exit;
}
