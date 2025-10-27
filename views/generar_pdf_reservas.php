<?php
/**
 * Archivo: views/generar_pdf_reservas.php
 * Descripción: Genera un PDF con el listado y resumen de reservas filtradas por rango de fechas.
 *
 * Requisitos:
 * - Librería FPDF ubicada en ../libs/fpdf/fpdf.php
 * - Tabla "reservas" con columnas: id_reserva, fk_usuario, fecha_reserva (Y-m-d), estado_reserva.
 *
 * Uso:
 * - Ver en navegador: generar_pdf_reservas.php?fechaInicio=2025-10-01&fechaFin=2025-10-27&salida=I
 * - Forzar descarga: generar_pdf_reservas.php?fechaInicio=2025-10-01&fechaFin=2025-10-27&salida=D
 */

declare(strict_types=1);

// ----------------------------------------------
// Configuración de errores 
// ----------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../libs/fpdf/fpdf.php';

// ----------------------------------------------
// Función: Crear conexión PDO a la base de datos
// ----------------------------------------------
function crearConexionPdo(): PDO
{
    $host = 'localhost';
    $nombreBaseDatos = 'senalibrary'; // <- nombre de la base de datos
    $usuario = 'root';               // <- nombre de tu usuario
    $clave = '';                     // <- nombre de tu clave

    $dsn = "mysql:host={$host};dbname={$nombreBaseDatos};charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $usuario, $clave, $opciones);
}

// ----------------------------------------------
// Función: Validar formato de fecha (Y-m-d)
// ----------------------------------------------
function esFechaYmdValida(string $fecha): bool
{
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt !== false && $dt->format('Y-m-d') === $fecha;
}

// ----------------------------------------------
// Función: Formatear fecha a formato latino (d/m/Y)
// ----------------------------------------------
function formatearFechaLatam(string $fecha): string
{
    $dt = new DateTime($fecha);
    return $dt->format('d/m/Y');
}

// ----------------------------------------------
// Clase: PDFReporte (encabezado y pie del PDF)
// ----------------------------------------------
class PDFReporte extends FPDF
{
    public string $tituloReporte = 'Reporte';
    public string $rangoFechas = '';

    // Encabezado del PDF
    public function Header(): void
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, utf8_decode($this->tituloReporte), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        if ($this->rangoFechas !== '') {
            $this->Cell(0, 6, utf8_decode('Rango: ' . $this->rangoFechas), 0, 0, 'L');
        }

        $this->Cell(0, 6, 'Fecha del reporte: ' . date('d/m/Y'), 0, 1, 'R');
        $this->Ln(4);
    }

    // Pie de página
    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// ----------------------------------------------
// Función: Obtener reservas por rango de fechas
// ----------------------------------------------
function obtenerReservasPorRango(PDO $conexion, string $fechaInicio, string $fechaFin): array
{
    $sql = "SELECT 
                r.id_reserva AS idReserva,
                r.fk_usuario AS idUsuario,
                r.fecha_reserva AS fechaReserva,
                r.estado_reserva AS estadoReserva,
                u.nombre_usuario AS nombreUsuario,
                u.apellido_usuario AS apellidoUsuario
            FROM reserva r
            INNER JOIN usuario u ON u.id_usuario = r.fk_usuario
            WHERE r.fecha_reserva BETWEEN :fechaInicio AND :fechaFin
            ORDER BY r.fecha_reserva DESC, r.id_reserva DESC";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':fechaInicio' => $fechaInicio,
        ':fechaFin'    => $fechaFin,
    ]);

    return $consulta->fetchAll();
}


// ----------------------------------------------
// Función: Contar reservas por estado y total
// ----------------------------------------------
function contarReservasPorEstado(array $reservas): array
{
    $resumen = ['total' => count($reservas)];

    foreach ($reservas as $reserva) {
        $estado = strtolower((string)($reserva['estadoReserva'] ?? 'desconocido'));
        if (!array_key_exists($estado, $resumen)) {
            $resumen[$estado] = 0;
        }
        $resumen[$estado]++;
    }

    return $resumen;
}

// ----------------------------------------------
// Función: Imprimir tabla principal de reservas
// ----------------------------------------------
function imprimirTablaReservas(PDFReporte $pdf, array $reservas): void
{
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(30, 9, 'ID Reserva', 1, 0, 'C', true);
    $pdf->Cell(60, 9, 'Usuario', 1, 0, 'C', true);
    $pdf->Cell(40, 9, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(60, 9, 'Estado', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);

    if (empty($reservas)) {
        $pdf->Cell(190, 8, 'No hay reservas en el rango seleccionado.', 1, 1, 'C');
        return;
    }

    foreach ($reservas as $fila) {
        $usuario = ucfirst($fila['nombreUsuario']) . ' ' . ucfirst($fila['apellidoUsuario']);
        $pdf->Cell(30, 8, (string)$fila['idReserva'], 1);
        $pdf->Cell(60, 8, utf8_decode($usuario), 1);
        $pdf->Cell(40, 8, formatearFechaLatam((string)$fila['fechaReserva']), 1);
        $pdf->Cell(60, 8, utf8_decode((string)$fila['estadoReserva']), 1, 1);
    }
}


// ----------------------------------------------
// Función: Imprimir resumen de reservas
// ----------------------------------------------
function imprimirResumenReservas(PDFReporte $pdf, array $resumen): void
{
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'Resumen', 0, 1, 'L');

    $pdf->SetFont('Arial', '', 10);

    // Ejemplo: "Total: X | pendiente: Y | confirmada: Z"
    $linea = 'Total: ' . ($resumen['total'] ?? 0);
    foreach ($resumen as $estado => $cantidad) {
        if ($estado === 'total') continue;
        $linea .= ' | ' . ucfirst($estado) . ': ' . $cantidad;
    }

    $pdf->MultiCell(0, 7, utf8_decode($linea), 0, 'L');
}

// ----------------------------------------------
// Bloque principal: Generar el PDF
// ----------------------------------------------
try {
    // Parámetros GET
    $fechaInicio = isset($_GET['fechaInicio']) && esFechaYmdValida($_GET['fechaInicio'])
        ? $_GET['fechaInicio'] : date('Y-m-01');

    $fechaFin = isset($_GET['fechaFin']) && esFechaYmdValida($_GET['fechaFin'])
        ? $_GET['fechaFin'] : date('Y-m-d');

    // Corrige si están invertidas
    if ($fechaInicio > $fechaFin) {
        [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
    }

    // Tipo de salida: I = ver, D = descargar
    $salida = (isset($_GET['salida']) && strtoupper($_GET['salida']) === 'D') ? 'D' : 'I';

    // Consultar datos
    $conexion = crearConexionPdo();
    $reservas = obtenerReservasPorRango($conexion, $fechaInicio, $fechaFin);
    $resumen = contarReservasPorEstado($reservas);

    // Generar PDF
    $pdf = new PDFReporte('L'); // L = horizontal, P = vertical
    $pdf->tituloReporte = 'Reporte de Reservas';
    $pdf->rangoFechas = formatearFechaLatam($fechaInicio) . ' a ' . formatearFechaLatam($fechaFin);

    $pdf->AddPage();
    imprimirTablaReservas($pdf, $reservas);
    imprimirResumenReservas($pdf, $resumen);

    // Limpia buffer antes de enviar PDF
    if (ob_get_length()) ob_end_clean();

    $nombreArchivo = "Reporte_Reservas_{$fechaInicio}_a_{$fechaFin}.pdf";
    $pdf->Output($salida, $nombreArchivo);
    exit;

} catch (Throwable $ex) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Error al generar el PDF de reservas: ' . $ex->getMessage();
    exit;
}
