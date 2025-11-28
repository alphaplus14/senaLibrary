<?php
/**
 * Archivo: views/generar_pdf_prestamos.php
 * Descripción: Genera un PDF con el listado de préstamos filtrados por rango de fechas.
 */

declare(strict_types=1);


error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../libs/fpdf/fpdf.php';

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

function esFechaYmdValida(string $fecha): bool
{
    $dt = DateTime::createFromFormat('Y-m-d', $fecha);
    return $dt !== false && $dt->format('Y-m-d') === $fecha;
}


function formatearFechaLatam(string $fecha): string
{
    $dt = new DateTime($fecha);
    return $dt->format('d/m/Y');
}

class PDFReporte extends FPDF
{
    public string $tituloReporte = 'Reporte';
    public string $rangoFechas = '';

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

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}


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

function imprimirTablaPrestamos(PDFReporte $pdf, array $prestamos): void
{
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(25, 9, 'ID Prestamo', 1, 0, 'C', true);
    $pdf->Cell(25, 9, 'ID Reserva', 1, 0, 'C', true);
    $pdf->Cell(60, 9, 'Usuario', 1, 0, 'C', true);
    $pdf->Cell(40, 9, 'Fecha Prestamo', 1, 0, 'C', true);
    $pdf->Cell(40, 9, 'Fecha Devolucion', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);

    if (empty($prestamos)) {
        $pdf->Cell(190, 8, 'No hay préstamos en el rango seleccionado.', 1, 1, 'C');
        return;
    }

    foreach ($prestamos as $fila) {
        $usuario = ucfirst($fila['nombreUsuario']) . ' ' . ucfirst($fila['apellidoUsuario']);
        $pdf->Cell(25, 8, (string)$fila['idPrestamo'], 1);
        $pdf->Cell(25, 8, (string)$fila['idReserva'], 1);
        $pdf->Cell(60, 8, utf8_decode($usuario), 1);
        $pdf->Cell(40, 8, formatearFechaLatam((string)$fila['fechaPrestamo']), 1);
        $pdf->Cell(40, 8, formatearFechaLatam((string)$fila['fechaDevolucion']), 1, 1);
    }
}


try {
    $fechaInicio = isset($_GET['fechaInicio']) && esFechaYmdValida($_GET['fechaInicio'])
        ? $_GET['fechaInicio'] : date('Y-m-01');

    $fechaFin = isset($_GET['fechaFin']) && esFechaYmdValida($_GET['fechaFin'])
        ? $_GET['fechaFin'] : date('Y-m-d');

    if ($fechaInicio > $fechaFin) {
        [$fechaInicio, $fechaFin] = [$fechaFin, $fechaInicio];
    }

    $salida = (isset($_GET['salida']) && strtoupper($_GET['salida']) === 'D') ? 'D' : 'I';

    // Consultar datos
    $conexion = crearConexionPdo();
    $prestamos = obtenerPrestamosPorRango($conexion, $fechaInicio, $fechaFin);

    // Generar PDF
    $pdf = new PDFReporte('L');
    $pdf->tituloReporte = 'Reporte de Préstamos';
    $pdf->rangoFechas = formatearFechaLatam($fechaInicio) . ' a ' . formatearFechaLatam($fechaFin);

    $pdf->AddPage();
    imprimirTablaPrestamos($pdf, $prestamos);

    // Limpia buffer antes de enviar PDF
    if (ob_get_length()) ob_end_clean();

    $nombreArchivo = "Reporte_Prestamos_{$fechaInicio}_a_{$fechaFin}.pdf";
    $pdf->Output($salida, $nombreArchivo);
    exit;

} catch (Throwable $ex) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Error al generar el PDF de préstamos: ' . $ex->getMessage();
    exit;
}
