<?php 
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['tipo_usuario'])){
  echo json_encode(["success" => false, "message" => "Sesión no válida"]);
  exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit();
}

$titulo    = $_POST['titulo'];
$autor     = $_POST['autor'];
$ISBN      = $_POST['ISBN'];
$cantidad  = $_POST['cantidad'];

$categorias = isset($_POST['categorias']) ? json_decode($_POST['categorias'], true) : [];  // ← CAMBIO AQUÍ

$consulta = "UPDATE libro
        SET titulo_libro='$titulo',
            autor_libro='$autor',
            ISBN_libro='$ISBN',
            cantidad_libro='$cantidad'
        WHERE id_libro='$id'";

$result = $mysql->efectuarConsulta($consulta);

if ($result === true) {

    // categorias actuales
    $actualesQuery = $mysql->efectuarConsulta("
        SELECT categorias_id_categoria FROM categorias_has_libro 
        WHERE libro_id_libro = $id
    ");

    $actuales = [];
    while ($fila = mysqli_fetch_assoc($actualesQuery)) {
        $actuales[] = intval($fila['categorias_id_categoria']);
    }

    // insertar nuevas
    foreach ($categorias as $id_categoria) {
        $id_categoria = intval($id_categoria);

        if (!in_array($id_categoria, $actuales)) {
            $mysql->efectuarConsulta("
                INSERT INTO categorias_has_libro (categorias_id_categoria, libro_id_libro)
                VALUES ($id_categoria, $id)
            ");
        }
    }

    // elimina las que no se necesitan
    foreach ($actuales as $catActual) {
        if (!in_array($catActual, $categorias)) {
            $mysql->efectuarConsulta("
                DELETE FROM categorias_has_libro 
                WHERE libro_id_libro = $id AND categorias_id_categoria = $catActual
            ");
        }
    }

    echo json_encode(["success" => true, "message" => "Libro actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$mysql->desconectar();
?>
