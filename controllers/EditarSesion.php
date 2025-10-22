<?php
require_once '../models/MySQL.php';
session_start();

// Verificar sesión
if (!isset($_SESSION['tipo_usuario'])) {
    header('Location: ../views/login.php');
    exit();
}

// Si llega por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $mysql = new MySQL();
    $mysql->conectar();

    $idUsuario = $_SESSION['id_usuario'];
    $nuevoNombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $nuevoApellido = htmlspecialchars(trim($_POST['apellido']), ENT_QUOTES, 'UTF-8');
    $nuevoEmail = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
// Si el usuario escribió una nueva contraseña, la encriptamos y actualizamos
if (!empty($_POST['password'])) {
    $nuevopassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $consulta = "UPDATE usuario 
                 SET nombre_usuario = '$nuevoNombre', 
                     apellido_usuario = '$nuevoApellido', 
                     email_usuario = '$nuevoEmail',
                     password_usuario = '$nuevopassword'
                 WHERE id_usuario = '$idUsuario'";
} else {
    // Si no escribió una nueva contraseña, no la modificamos
    $consulta = "UPDATE usuario 
                 SET nombre_usuario = '$nuevoNombre', 
                     apellido_usuario = '$nuevoApellido', 
                     email_usuario = '$nuevoEmail'
                 WHERE id_usuario = '$idUsuario'";
}


    $resultado = $mysql->efectuarConsulta($consulta);

    if ($resultado) {
        // Actualizar también los datos de sesión
        $_SESSION['nombre_usuario'] = $nuevoNombre;
        $_SESSION['apellido_usuario'] = $nuevoApellido;
        $_SESSION['email_usuario'] = $nuevoEmail;
    }

    $mysql->desconectar();
} else {
    header('Location: ../views/perfilUsuario.php');
    exit();
}
?>


