<?php
require_once '../models/MySQL.php';
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'Administrador') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit();
    }

    if (!isset($_POST['id_reserva'])) {
        echo json_encode(['success' => false, 'message' => 'ID de reserva no proporcionado']);
        exit();
    }

    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Verificar que la reserva exista y esté pendiente
    $queryVerificar = "SELECT estado_reserva FROM reserva WHERE id_reserva = $idReserva";
    $resultadoVerificar = $mysql->efectuarConsulta($queryVerificar);
    
    if (mysqli_num_rows($resultadoVerificar) === 0) {
        echo json_encode(['success' => false, 'message' => 'La reserva no existe']);
        $mysql->desconectar();
        exit();
    }
    
    $filaReserva = mysqli_fetch_assoc($resultadoVerificar);
    if ($filaReserva['estado_reserva'] !== 'Pendiente') {
        echo json_encode(['success' => false, 'message' => 'La reserva ya fue procesada anteriormente']);
        $mysql->desconectar();
        exit();
    }

    // Obtener los libros de la reserva
    $queryLibros = "SELECT libro_id_libro FROM reserva_has_libro WHERE reserva_id_reserva = $idReserva";
    $resultadoLibros = $mysql->efectuarConsulta($queryLibros);

    if (mysqli_num_rows($resultadoLibros) > 0) {

        // Cambiar el estado de la reserva a Aprobada
        $queryActualizar = "UPDATE reserva SET estado_reserva = 'Aprobada' WHERE id_reserva = $idReserva";
        $resultadoActualizar = $mysql->efectuarConsulta($queryActualizar);

        // Registrar prestamo
        $queryPrestamo = "INSERT INTO prestamo (fk_reserva, fecha_prestamo) VALUES ($idReserva, NOW())";
        $resultadoPrestamo = $mysql->efectuarConsulta($queryPrestamo);

        $mysql->desconectar();

        if ($resultadoActualizar && $resultadoPrestamo) {
            echo json_encode(['success' => true, 'message' => 'Reserva aprobada y préstamo registrado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la reserva o registrar el préstamo']);
        }

    } else {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'No se encontraron libros en la reserva']);
    }

} catch (Exception $e) {
    if (isset($mysql)) {
        @$mysql->desconectar();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>