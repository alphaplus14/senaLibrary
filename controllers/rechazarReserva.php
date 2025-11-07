<?php
require_once '../models/MySQL.php';
session_start();

header('Content-Type: application/json');

try {
    // Validar usuario administrador
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'Administrador') {
        throw new Exception('No autorizado');
    }

    // Validar id de reserva
    if (empty($_POST['id_reserva'])) {
        throw new Exception('ID de reserva no proporcionado');
    }

    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Obtener los libros asociados a la reserva
    $queryLibros = "SELECT libro_id_libro FROM reserva_has_libro WHERE reserva_id_reserva = $idReserva";
    $resultadoLibros = $mysql->efectuarConsulta($queryLibros);

    if (!$resultadoLibros || mysqli_num_rows($resultadoLibros) === 0) {
        throw new Exception('No se encontraron libros en la reserva');
    }

    // Cambiar el estado de la reserva a "Rechazada"
    $query = "UPDATE reserva SET estado_reserva = 'Rechazada' WHERE id_reserva = $idReserva";
    $resultado = $mysql->efectuarConsulta($query);
    if (!$resultado) {
        throw new Exception('Error al actualizar el estado de la reserva');
    }

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Reserva rechazada exitosamente'
        ]);
    } else {
        throw new Exception('Error al rechazar la reserva');
    }

} catch (Exception $e) {
    // Captura de errores 
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    // Cerrar conexion si existe y no agrega nada a las tablas
    try {
        if (isset($mysql)) {
            @$mysql->desconectar();
        }
    } catch (Throwable $t) {
        // Ignorar error si ya estaba cerrada
    }
}
?>
