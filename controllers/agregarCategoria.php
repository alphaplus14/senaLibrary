<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();


if (!isset($_SESSION['tipo_usuario'])) {
    header("location: ./login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $mysql = new MySQL();
    $mysql->conectar();

    // Validar campos requeridos
    $required = ['nombre_categoria'];
    foreach ($required as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }
//sanitizar y asignar variables
    $categoria = htmlspecialchars(strtolower(trim($_POST['nombre_categoria'])), ENT_QUOTES, 'UTF-8');

    // Insertar libro
    $consultaInsert = "
        INSERT INTO categorias (nombre_categoria)
        VALUES ('$categoria')
    ";

    if ($mysql->efectuarConsulta($consultaInsert)) {
        $id = mysqli_insert_id($mysql->conectar());
        echo json_encode(['success' => true, 'message' => 'Categoria agregada exitosamente.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Error al agregar la categoria.']);
    }

    $mysql->desconectar();
}
?>

