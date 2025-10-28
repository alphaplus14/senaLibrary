<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Verificar que la reserva exista
    $queryVerificar = "SELECT id_reserva FROM reserva WHERE id_reserva = $idReserva";
    $resultadoVerificar = $mysql->efectuarConsulta($queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        // Solo cambiar el estado
        $queryCancelar = "UPDATE reserva SET estado_reserva = 'Cancelada' WHERE id_reserva = $idReserva";
        $resultadoCancelar = $mysql->efectuarConsulta($queryCancelar);

        $mysql->desconectar();

        if ($resultadoCancelar) {
            echo json_encode(['success' => true, 'message' => 'Reserva cancelada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva']);
        }
    } else {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'La reserva no existe']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
?>
