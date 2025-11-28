<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_prestamo'])) {

    $idPrestamo = intval($_POST['id_prestamo']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Aumentar fecha_devolucion_prestamo por 7 días
    $query = "
        UPDATE prestamo 
        SET fecha_devolucion_prestamo = DATE_ADD(fecha_devolucion_prestamo, INTERVAL 7 DAY)
        WHERE id_prestamo = '$idPrestamo'
    ";

    $resultado = $mysql->efectuarConsulta($query);

    $mysql->desconectar();

    echo json_encode([
        'success' => true,
        'message' => 'El préstamo fue renovado correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud inválida.'
    ]);
}
