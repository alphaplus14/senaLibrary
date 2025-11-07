<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva= intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    $query = "
      select reserva.id_reserva,reserva.fecha_reserva,reserva.estado_reserva,libro.id_libro,libro.ISBN_libro,libro.titulo_libro,libro.autor_libro,libro.categoria_libro,usuario.nombre_usuario from reserva inner join reserva_has_libro on reserva_has_libro.reserva_id_reserva=reserva.id_reserva inner join libro on reserva_has_libro.libro_id_libro=libro.id_libro inner join usuario on reserva.fk_usuario=usuario.id_usuario where reserva.id_reserva='$idReserva';
    ";

    $resultado = $mysql->efectuarConsulta($query);
    $detalle = [];

    while ($row = mysqli_fetch_assoc($resultado)) {
        $detalle[] = $row;
    }

    $mysql->desconectar();

    echo json_encode(['success' => true, 'detalle' => $detalle]);
} else {
   
    echo json_encode(['success' => false, 'message' => 'ID de Reserva no válido']);
}
