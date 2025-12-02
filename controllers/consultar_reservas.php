<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../models/MySQL.php';

try {
    $db = new MySQL();
    $conexion = $db->conectar();


    $consulta = "
        SELECT 
            l.titulo_libro,
            l.autor_libro,
            COUNT(rhl.libro_id_libro) AS total_prestamos
        FROM 
            prestamo p
        INNER JOIN 
            reserva r ON p.fk_reserva = r.id_reserva
        INNER JOIN 
            reserva_has_libro rhl ON r.id_reserva = rhl.reserva_id_reserva
        INNER JOIN 
            libro l ON rhl.libro_id_libro = l.id_libro
        GROUP BY 
            l.id_libro, l.titulo_libro, l.autor_libro
        ORDER BY 
            total_prestamos DESC
        LIMIT 5
    ";
    
    $resultado = mysqli_query($conexion, $consulta);
    
    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    
    $libros = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = [
            'titulo' => $fila['titulo_libro'],
            'autor' => $fila['autor_libro'],
            'total' => (int)$fila['total_prestamos']
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $libros,
        'total_registros' => count($libros)
    ]);
    
    $db->desconectar();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>