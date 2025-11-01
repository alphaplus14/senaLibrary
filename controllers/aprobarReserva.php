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

    // Obtener los libros de la reserva
    $queryLibros = "SELECT libro_id_libro FROM reserva_has_libro WHERE reserva_id_reserva = $idReserva";
    $resultadoLibros = $mysql->efectuarConsulta($queryLibros);

    if (mysqli_num_rows($resultadoLibros) > 0) {

        // Cambiar el estado de la reserva
        $query = "UPDATE reserva SET estado_reserva = 'Aprobada' WHERE id_reserva = $idReserva";
        $resultado = $mysql->efectuarConsulta($query);

        // Registrar prestamo
        $queryPrestamos = "INSERT INTO prestamo (fk_reserva, fecha_prestamo) VALUES ($idReserva, NOW())";
        $resultadoPrestamo = $mysql->efectuarConsulta($queryPrestamos);

        // Descontar stock de libros
        $todosActualizados = true;
        while ($libro = mysqli_fetch_assoc($resultadoLibros)) {
            $queryStock = "UPDATE libro SET cantidad_libro = cantidad_libro - 1 WHERE id_libro = " . $libro['libro_id_libro'];
            $resultadoStock = $mysql->efectuarConsulta($queryStock);
            if (!$resultadoStock) $todosActualizados = false;
        }

        if ($resultado && $resultadoPrestamo && $todosActualizados) {
            echo json_encode(['success' => true, 'message' => 'Reserva aprobada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al aprobar la reserva']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron libros en la reserva']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Intentar cerrar, pero sin acceder a las propiedades del modelo
    try {
        if (isset($mysql)) {
            @$mysql->desconectar();
        }
    } catch (Throwable $e) {
        // Ignorar si ya esta cerrada
    }
}
?>
