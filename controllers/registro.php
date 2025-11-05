<?php
require_once '../models/MySQL.php';
session_start();

if (isset($_POST['registrar'])) {

    $mysql = new MySQL();
    $mysql->conectar();

    $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $apellido = htmlspecialchars(trim($_POST['apellido']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encriptado
    $tipo = 'Cliente'; // solo usuarios normales

    // Verificar si el correo ya existe
    $resultado = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE email_usuario='$email'");
    if (mysqli_num_rows($resultado) > 0) {
        header("Location: ../views/registro.php?error=correo");
        exit();
    }

    // Insertar nuevo usuario
    $query = "INSERT INTO usuario (nombre_usuario, apellido_usuario, email_usuario, password_usuario, tipo_usuario, estado)
              VALUES ('$nombre', '$apellido', '$email', '$password', '$tipo', 'Activo')";

    if ($mysql->efectuarConsulta($query)) {
        header("Location: ../views/login.php?registro=ok");
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }

    $mysql->desconectar();
} else {
    header("Location: ../views/registro.php");
    exit();
}
?>

