<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_libro'])) {
    $idLibro= intval($_POST['id_libro']);

    $mysql = new MySQL();
    $mysql->conectar();

    $query = "
       SELECT 
        * from libro where id_libro=$idLibro;
    ";

    $resultado = $mysql->efectuarConsulta($query);
    $detalle = [];

    while ($row = mysqli_fetch_assoc($resultado)) {
        $detalle[] = $row;
    }

    $mysql->desconectar();

    echo json_encode(['success' => true, 'detalle' => $detalle]);
} else {
   
    echo json_encode(['success' => false, 'message' => 'ID de venta no válido']);
}
