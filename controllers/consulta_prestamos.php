<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();

    // Contar el total de registros en la tabla "prestamo"
    $consulta = "SELECT COUNT(*) AS total_prestamos FROM prestamo";
    $resultado = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_assoc($resultado);

    echo json_encode($data);
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
