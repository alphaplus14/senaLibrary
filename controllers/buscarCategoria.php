<?php
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['tipo_usuario'])) {
  echo json_encode(["success" => false, "message" => "Sesión no válida"]);
  exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$query = $_POST['query'];

// Consulta especifica
$resultado = $mysql->efectuarConsulta("
    SELECT id_categoria as id, nombre_categoria 
    FROM categorias 
    WHERE nombre_categoria LIKE '%$query%' 
    LIMIT 10
");

$categorias = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $categorias[] = $row;
}

echo json_encode($categorias);

$mysql->desconectar();
?>