<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../modelo/MySQL.php';
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
    $required = ['nombre_usuario', 'apellido_usuario', 'email_usuario', 'password_usuario', 'tipo_usuario'];
    foreach ($required as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }

    $nombre   = htmlspecialchars(trim($_POST['nombre_usuario']), ENT_QUOTES, 'UTF-8');
    $apellido = htmlspecialchars(trim($_POST['apellido_usuario']), ENT_QUOTES, 'UTF-8');
    $email    = htmlspecialchars(trim($_POST['email_usuario']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password_usuario'];
    $tipo     = htmlspecialchars(trim($_POST['tipo_usuario']), ENT_QUOTES, 'UTF-8');

    // Verificar si el correo ya está registrado
    $consultaExiste = "SELECT id_usuario FROM usuario WHERE email_usuario = '$email'";
    $resultado = $mysql->efectuarConsulta($consultaExiste);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
        $mysql->desconectar();
        exit;
    }

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario
    $consultaInsert = "
        INSERT INTO usuario (nombre_usuario, apellido_usuario, email_usuario, password_usuario, tipo_usuario)
        VALUES ('$nombre', '$apellido', '$email', '$hash', '$tipo')
    ";

    if ($mysql->efectuarConsulta($consultaInsert)) {
        echo json_encode(['success' => true, 'message' => 'Usuario agregado exitosamente.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Error al agregar el usuario.']);
    }

    $mysql->desconectar();
}
?>

