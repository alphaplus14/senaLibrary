<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    //consulta para mostrar los datos en el sweet alert 
    $query = "SELECT  reserva.id_reserva,
            reserva.fecha_reserva,
            reserva.estado_reserva,
            libro.id_libro,
            libro.ISBN_libro,
            libro.titulo_libro,
            libro.autor_libro,
            GROUP_CONCAT(DISTINCT categorias.nombre_categoria SEPARATOR ', ') as categorias,
            usuario.nombre_usuario
        FROM reserva 
        INNER JOIN reserva_has_libro ON reserva_has_libro.reserva_id_reserva = reserva.id_reserva 
        INNER JOIN libro ON reserva_has_libro.libro_id_libro = libro.id_libro 
        LEFT JOIN categorias_has_libro ON categorias_has_libro.libro_id_libro = libro.id_libro
        LEFT JOIN categorias ON categorias.id_categoria = categorias_has_libro.categorias_id_categoria
        INNER JOIN usuario ON reserva.fk_usuario = usuario.id_usuario 
        WHERE reserva.id_reserva = $idReserva
        GROUP BY 
            reserva.id_reserva,
            reserva.fecha_reserva,
            reserva.estado_reserva,
            libro.id_libro,
            libro.ISBN_libro,
            libro.titulo_libro,
            libro.autor_libro,
            usuario.nombre_usuario
    ";

    $resultado = $mysql->efectuarConsulta($query);
    $detalle = [];

    while ($row = mysqli_fetch_assoc($resultado)) {
        $detalle[] = $row;
    }

    $mysql->desconectar();

    if (count($detalle) > 0) {
        echo json_encode(['success' => true, 'detalle' => $detalle]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos para esta reserva']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'ID de Reserva no válido']);
}
?>