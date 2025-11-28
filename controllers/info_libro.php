<?php  
require_once '../models/MySQL.php';

header('Content-Type: application/json');

// Buena practica verificar el metodo y que no este vacio el id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $id = intval($_POST['id']);
    
    $mysql = new MySQL();
    $mysql->conectar();
    
    $consulta = $mysql->efectuarConsulta("SELECT * FROM libro WHERE id_libro = $id");
    
    if ($consulta->num_rows > 0) {

        $informacion = $consulta->fetch_assoc();
        
        // Obtener categorias
        $queryCategorias = "
            SELECT c.id_categoria, c.nombre_categoria
            FROM categorias c
            INNER JOIN categorias_has_libro cl 
                ON c.id_categoria = cl.categorias_id_categoria
            WHERE cl.libro_id_libro = $id
        ";

        $resultadoCategorias = $mysql->efectuarConsulta($queryCategorias);

        $categorias_ids = [];
        $categorias_nombres = [];
        
        while ($cat = $resultadoCategorias->fetch_assoc()) {
            $categorias_ids[] = intval($cat['id_categoria']);
            $categorias_nombres[] = $cat['nombre_categoria'];
        }
        
        echo json_encode([
            'success' => true,
            'data' => [
                'id_libro' => $informacion['id_libro'],
                'titulo_libro' => $informacion['titulo_libro'],
                'autor_libro' => $informacion['autor_libro'],
                'ISBN_libro' => $informacion['ISBN_libro'],
                'categorias_ids' => $categorias_ids,
                'categorias_nombres' => implode(', ', $categorias_nombres),

                'cantidad_libro' => $informacion['cantidad_libro'],
                'disponibilidad_libro' => $informacion['disponibilidad_libro'],
            ]
        ]);

    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Libro no encontrado'
        ]);
    }
    
    $mysql->desconectar();

} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID no proporcionado'
    ]);
}
?>
