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
                'id_usuario' => $informacion['id_usuario'],
                'nombre_usuario' => $informacion['nombre_usuario'],
                'apellido_usuario' => $informacion['apellido_usuario'],
                'email_usuario' => $informacion['email_usuario'],
                'tipo_usuario' => $informacion['tipo_usuario'],
                'estado' => $informacion['estado'],
                
            ]
        ]);
}
else{
    http_response_code(404);
    echo json_encode('error');
}
$mysql->desconectar();
}