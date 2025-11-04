<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();

    // Total de registros en la tabla reserva
    $consulta = "SELECT COUNT(*) AS total_reservas FROM reserva";
    $resultado = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_assoc($resultado);

    echo json_encode($data);
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
