<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Obtener el id de los libros de la reserva
    $queryLibros = "SELECT libro_id_libro FROM reserva_has_libro WHERE reserva_id_reserva = $idReserva";
    $resultadoLibros = $mysql->efectuarConsulta($queryLibros);

    // Verificar que hay libros
    if (mysqli_num_rows($resultadoLibros) > 0) {
        // Actualizar el estado de la reserva
        $query1 = "UPDATE reserva SET estado_reserva = 'Cancelada' WHERE id_reserva = $idReserva";
        $resultado1 = $mysql->efectuarConsulta($query1);

        // Actualizar el stock de cada libro
        $todosActualizados = true;
        while ($libro = mysqli_fetch_assoc($resultadoLibros)) {
            $query2 = "UPDATE libro SET cantidad_libro = cantidad_libro + 1 WHERE id_libro = " . $libro['libro_id_libro'];
            $resultado2 = $mysql->efectuarConsulta($query2);
            
            if (!$resultado2) {
                $todosActualizados = false;
            }
        }

        $mysql->desconectar();

        if ($resultado1 && $todosActualizados) {
            echo json_encode(['success' => true, 'message' => 'Reserva cancelada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva']);
        }
    } else {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'No se encontraron libros en la reserva']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
?>