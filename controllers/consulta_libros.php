<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();

    $consulta = "SELECT SUM(cantidad_libro) AS total_libros FROM libro";
    $resultado = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_assoc($resultado);

    echo json_encode($data);
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

