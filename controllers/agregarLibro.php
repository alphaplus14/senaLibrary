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
    $required = ['titulo_libro', 'autor_libro', 'ISBN_libro', 'categoria_libro', 'cantidad_libro'];
    foreach ($required as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }
//sanitizar y asignar variables
    $titulo   = htmlspecialchars(trim($_POST['titulo_libro']), ENT_QUOTES, 'UTF-8');
    $autor = htmlspecialchars(trim($_POST['autor_libro']), ENT_QUOTES, 'UTF-8');
    $iSBN_libro   = htmlspecialchars(trim($_POST['ISBN_libro']), ENT_QUOTES, 'UTF-8');
    $categoria    = htmlspecialchars(trim($_POST['categoria_libro']), ENT_QUOTES, 'UTF-8');
    $cantidad_libro = htmlspecialchars(trim($_POST['cantidad_libro']), ENT_QUOTES, 'UTF-8');

    // Verificar si el ISBN ya está registrado
    $consultaExiste = "SELECT ISBN_libro FROM libro WHERE ISBN_libro = '$iSBN_libro'";
    $resultado = $mysql->efectuarConsulta($consultaExiste);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo json_encode(['success' => false, 'message' => 'El ISBN ya está registrado.']);
        $mysql->desconectar();
        exit;
    }

    $estado= "Disponible";
    // Insertar libro
    $consultaInsert = "
        INSERT INTO libro (titulo_libro, autor_libro, ISBN_libro, categoria_libro, cantidad_libro,disponibilidad_libro)
        VALUES ('$titulo', '$autor', '$iSBN_libro', '$categoria', '$cantidad_libro','$estado')
    ";

    if ($mysql->efectuarConsulta($consultaInsert)) {
        echo json_encode(['success' => true, 'message' => 'Libro agregado exitosamente.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Error al agregar el libro.']);
    }

    $mysql->desconectar();
}
?>

