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

if (isset($_POST['texto_busqueda'])) {
    $texto = trim($_POST['texto_busqueda']);
}

$mysql = new MySQL();
$mysql->conectar();

$query = $_POST['query'];
$resultado = $mysql->efectuarConsulta("SELECT * FROM libro WHERE titulo_libro LIKE '%$query%'  or autor_libro LIKE '%$query%' LIMIT 10");

$libros= [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $libros[] = $row;
}
echo json_encode($libros);

$mysql->desconectar();
?>

