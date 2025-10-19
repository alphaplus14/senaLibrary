<?php 
require_once '../models/MySQL.php';


//buena practica verificar el metodo y que no este vacio el id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
$id=intval($_POST['id']);

$mysql = new MySQL();
$mysql->conectar();

$consulta=$mysql->efectuarConsulta("SELECT * from usuario where id_usuario=$id");

if($consulta->num_rows>0){
    $informacion=$consulta->fetch_assoc();
     echo json_encode([
        'success' => true,
            'data' => [
                'id' => $informacion['id'],
                'nombre' => $informacion['nombre'],
                'cargo_id' => $informacion['cargo_id'],
                'estado' => $informacion['estado'],
                'correo' => $informacion['correo'],
                
            ]
        ]);
}
else{
    http_response_code(404);
    echo json_encode('error');
}
$mysql->desconectar();
}