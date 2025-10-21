<?php 
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['tipo_usuario'])){
  echo json_encode(["success" => false, "message" => "Sesión no válida"]);
  exit();
}
//conexion 
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit();
}


$titulo       = $_POST['titulo'];
$autor      =$_POST['autor'];
$ISBN       = $_POST['ISBN'];
$categoria        = $_POST['categoria'];
$cantidad  = $_POST['cantidad']; 

 // Verificar si el ISBN ya está registrado
    $consultaExiste = "SELECT ISBN_libro FROM libro WHERE ISBN_libro = '$iSBN_libro'";
    $resultado = $mysql->efectuarConsulta($consultaExiste);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo json_encode(['success' => false, 'message' => 'El ISBN yaestá registrado.']);
        $mysql->desconectar();
        exit;
    }

$consulta = "UPDATE libro
        SET titulo_libro='$titulo',
            autor_libro='$autor',
            ISBN_libro='$ISBN',
            categoria_libro='$categoria',
            cantidad_libro='$cantidad'
        WHERE id_libro='$id'";


$result = $mysql->efectuarConsulta($consulta);

if ($result === true) {
    echo json_encode(["success" => true, "message" => "Libro actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$mysql->desconectar();
