<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();

    // Consulta para obtener el top 5 de usuarios 
    $consulta = "
        SELECT 
            u.nombre_usuario,
            u.apellido_usuario,
            COUNT(rhl.libro_id_libro) AS total_libros_solicitados,
            COUNT(DISTINCT r.id_reserva) AS total_reservas
        FROM 
            reserva r
        INNER JOIN 
            usuario u ON r.fk_usuario = u.id_usuario
        INNER JOIN 
            reserva_has_libro rhl ON r.id_reserva = rhl.reserva_id_reserva
        GROUP BY 
            u.id_usuario, u.nombre_usuario, u.apellido_usuario
        ORDER BY 
            total_libros_solicitados DESC
        LIMIT 5
    ";
    
    $resultado = mysqli_query($conexion, $consulta);
    
    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    
    $usuarios = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = [
            'nombre_completo' => $fila['nombre_usuario'] . ' ' . $fila['apellido_usuario'],
            'nombre' => $fila['nombre_usuario'],
            'apellido' => $fila['apellido_usuario'],
            'total_libros' => (int)$fila['total_libros_solicitados'],
            'total_reservas' => (int)$fila['total_reservas']
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $usuarios,
        'total_registros' => count($usuarios)
    ]);
    
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>