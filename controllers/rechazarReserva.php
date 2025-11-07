<?php
require_once '../models/MySQL.php';
session_start();

header('Content-Type: application/json');

try {

    // Validar usuario administrador
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'Administrador') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit();
    }

    // Validar id de reserva
    if (!isset($_POST['id_reserva'])) {
        echo json_encode(['success' => false, 'message' => 'ID de reserva no proporcionado']);
        exit();
    }

    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Verificar que la reserva esté pendiente
    $reserva = $mysql->efectuarConsulta("SELECT estado_reserva FROM reserva WHERE id_reserva = $idReserva");
    $res = mysqli_fetch_assoc($reserva);

    if (!$res) {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'La reserva no existe']);
        exit();
    }

    if ($res['estado_reserva'] != 'Pendiente') {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'La reserva ya fue procesada anteriormente']);
        exit();
    }

    // Rechazar reserva
    $actualizar = $mysql->efectuarConsulta("UPDATE reserva SET estado_reserva = 'Rechazada' WHERE id_reserva = $idReserva");

    if (!$actualizar) {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'Error al rechazar la reserva']);
        exit();
    }

    $mysql->desconectar();

    echo json_encode(['success' => true, 'message' => 'Reserva rechazada correctamente']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
