<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();

    // Consulta para contar el total de prestamitos cesar
    $consulta = "SELECT COUNT(*) AS total_prestamos FROM prestamo";
    
    $resultado = mysqli_query($conexion, $consulta);
    
    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    
    $data = mysqli_fetch_assoc($resultado);
    
    echo json_encode([
        'success' => true,
        'total' => (int)$data['total_prestamos']
    ]);
    
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>