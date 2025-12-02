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

    // Validar campos
    $required = ['titulo_libro', 'autor_libro', 'ISBN_libro', 'categoria_libro', 'cantidad_libro'];
    foreach ($required as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }

    // Sanitizar
    $titulo = htmlspecialchars(trim($_POST['titulo_libro']), ENT_QUOTES, 'UTF-8');
    $autor = htmlspecialchars(trim($_POST['autor_libro']), ENT_QUOTES, 'UTF-8');
    $isbn = htmlspecialchars(trim($_POST['ISBN_libro']), ENT_QUOTES, 'UTF-8');
    $cantidad = intval($_POST['cantidad_libro']);
    
    $categorias = [];

if (isset($_POST['categoria_libro']) && !empty($_POST['categoria_libro'])) {
    $categorias = json_decode($_POST['categoria_libro'], true);

    if (!is_array($categorias)) {
        $categorias = [];
    }
}
    // Verificar ISBN
    $existe = $mysql->efectuarConsulta("SELECT ISBN_libro FROM libro WHERE ISBN_libro = '$isbn'");
    if ($existe && mysqli_num_rows($existe) > 0) {
        echo json_encode(['success' => false, 'message' => 'El ISBN ya está registrado.']);
        exit;
    }

    $categoria = $categorias[0];

    // Insertar libro
    $consultaInsert = "
        INSERT INTO libro (titulo_libro, autor_libro, ISBN_libro, categoria_libro, cantidad_libro,disponibilidad_libro)
        VALUES ('$titulo', '$autor', '$iSBN_libro', '$categoria', '$cantidad_libro','$estado')
    ";

    if ($mysql->efectuarConsulta($consultaInsert)) {
        echo json_encode(['success' => true, 'message' => 'Libro agregado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el libro.']);
    }

    $mysql->desconectar();
}
?>

