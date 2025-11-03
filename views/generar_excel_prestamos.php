<?php
/**
 * Archivo: views/generar_excel_prestamos.php
 * Descripción: Genera un archivo Excel con el listado de préstamos filtrados por rango de fechas.
 */

declare(strict_types=1);

// ----------------------------------------------
// Configuración de errores
// ----------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Librería PHPSpreadsheet (requiere composer o instalación previa)
require_once __DIR__ . '/../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ----------------------------------------------
// Función: Crear conexión PDO a la base de datos
// ----------------------------------------------
function crearConexionPdo(): PDO
{
    $host = 'localhost';
    $nombreBaseDatos = 'senalibrary';
    $usuario = 'root';
    $clave = '';

    $dsn = "mysql:host={$host};dbname={$nombreBaseDatos};charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $usuario, $clave, $opciones);
}

// ----------------------------------------------
// Validar formato de fecha (Y-m-d)
// ----------------------------------------------
function esFechaYmdValida(string $fecha): bool
{
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt !== false && $dt->format('Y-m-d') === $fecha;
}

// ----------------------------------------------
// Obtener préstamos por rango de fechas
// ----------------------------------------------
function obtenerPrestamosPorRango(PDO $conexion, string $fechaInicio, string $fechaFin): array
{
    $sql = "SELECT 
                p.id_prestamo AS idPrestamo,
                r.id_reserva AS idReserva,
                u.nombre_usuario AS nombreUsuario,
                u.apellido_usuario AS apellidoUsuario,
                p.fecha_prestamo AS fechaPrestamo,
                p.fecha_devolucion_prestamo AS fechaDevolucion
            FROM prestamo p
            INNER JOIN reserva r ON r.id_reserva = p.fk_reserva
            INNER JOIN usuario u ON u.id_usuario = r.fk_usuario
            WHERE p.fecha_prestamo BETWEEN :fechaInicio AND :fechaFin
            ORDER BY p.fecha_prestamo DESC, p.id_prestamo DESC";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':fechaInicio' => $fechaInicio,
        ':fechaFin'    => $fechaFin,
    ]);

    return $consulta->fetchAll();
}

// ----------------------------------------------
// BLOQUE PRINCIPAL
// ----------------------------------------------
try {
    $fechaInicio = isset($_GET['fechaInicio']) && esFechaYmdValida($_GET['fechaInicio'])
        ? $_GET['fechaInicio'] : date('Y-m-01');
    $fechaFin = isset($_GET['fechaFin']) && esFechaYmdValida($_GET['fechaFin'])
        ? $_GET['fechaFin'] : date('Y-m-d');

    if ($fechaInicio > $fechaFin) {
        [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
    }

    $conexion = crearConexionPdo();
    $prestamos = obtenerPrestamosPorRango($conexion, $fechaInicio, $fechaFin);

    // Crear hoja Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Préstamos');

    // Encabezado principal
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'REPORTE DE PRÉSTAMOS');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Subtítulo (rango de fechas)
    $sheet->mergeCells('A2:F2');
    $sheet->setCellValue('A2', "Rango: {$fechaInicio} a {$fechaFin}");
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Cabeceras
    $cabeceras = ['ID Prestamo', 'ID Reserva', 'Usuario', 'Apellido', 'Fecha Prestamo', 'Fecha Devolucion'];
    $sheet->fromArray($cabeceras, null, 'A4');

    // Estilo de cabeceras
    $sheet->getStyle('A4:F4')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => '4472C4']
        ]
    ]);

    // Datos
    $fila = 5;
    foreach ($prestamos as $prestamo) {
        $sheet->setCellValue("A{$fila}", $prestamo['idPrestamo']);
        $sheet->setCellValue("B{$fila}", $prestamo['idReserva']);
        $sheet->setCellValue("C{$fila}", ucfirst($prestamo['nombreUsuario']));
        $sheet->setCellValue("D{$fila}", ucfirst($prestamo['apellidoUsuario']));
        $sheet->setCellValue("E{$fila}", $prestamo['fechaPrestamo']);
        $sheet->setCellValue("F{$fila}", $prestamo['fechaDevolucion']);
        $fila++;
    }

    // Bordes para todos los datos
    $sheet->getStyle("A4:F" . ($fila - 1))->applyFromArray([
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);

    // Autoajustar ancho de columnas
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Nombre del archivo
    $nombreArchivo = "Reporte_Prestamos_{$fechaInicio}_a_{$fechaFin}.xlsx";

    // Encabezados para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
    header('Cache-Control: max-age=0');

    // Generar archivo Excel y enviarlo
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Throwable $ex) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Error al generar el Excel de préstamos: ' . $ex->getMessage();
    exit;
}
