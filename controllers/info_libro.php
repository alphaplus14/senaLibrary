<?php 
require_once '../models/MySQL.php';


//verificar el metodo y que no este vacio el id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
$id=intval($_POST['id']);

$mysql = new MySQL();
$mysql->conectar();

$consulta=$mysql->efectuarConsulta("SELECT * from libro where id_libro=$id");

if($consulta->num_rows>0){
    $informacion=$consulta->fetch_assoc();
     echo json_encode([
        'success' => true,
            'data' => [
                'id_libro' => $informacion['id_libro'],
                'titulo_libro' => $informacion['titulo_libro'],
                'autor_libro' => $informacion['autor_libro'],
                'ISBN_libro' => $informacion['ISBN_libro'],
                'categoria_libro' => $informacion['categoria_libro'],
                'cantidad_libro' => $informacion['cantidad_libro'],
                'disponibilidad_libro' => $informacion['disponibilidad_libro'],
                
            ]
        ]);
}
else{
    http_response_code(404);
    echo json_encode('error');
}
$mysql->desconectar();
}