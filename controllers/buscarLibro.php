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


$consulta = $mysql->efectuarConsulta("
  SELECT id_libro,titulo_libro, autor_libro, categoria_libro, cantidad_libro
  FROM libro
  WHERE titulo_libro LIKE '%$texto%' OR autor_libro LIKE '%$texto%'
");

if ($consulta && $consulta->num_rows > 0) {
  $data = [];
  while ($row = $consulta->fetch_assoc()) {
    $data[] = [
      'id_libro' => $row['id_libro'],
      'titulo_libro' => $row['titulo_libro'],
      'autor_libro' => $row['autor_libro'],
      'categoria_libro' => $row['categoria_libro'],
      'cantidad_libro' => $row['cantidad_libro']
    ];
  }
  echo json_encode(['encontrados' => true, 'data' => $data]);
} else {
  echo json_encode(['encontrados' => false]);
}

$mysql->desconectar();
?>
