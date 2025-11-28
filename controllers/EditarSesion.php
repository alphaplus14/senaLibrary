<?php
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    echo "sin_sesion";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysql = new MySQL();
    $mysql->conectar();

    $idUsuario = $_SESSION['id_usuario'];
    $nuevoNombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $nuevoApellido = htmlspecialchars(trim($_POST['apellido']), ENT_QUOTES, 'UTF-8');
    $nuevoEmail = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');

    // Comenzamos la base de la consulta
    $consultaBase = "UPDATE usuario SET 
        nombre_usuario = '$nuevoNombre',
        apellido_usuario = '$nuevoApellido',
        email_usuario = '$nuevoEmail'";

    // Si el usuario quiere cambiar la contraseña
    if (!empty($_POST['password'])) {
        $passwordActual = $_POST['password_actual'] ?? '';

        // Verificamos la contraseña actual
        $resultado = $mysql->efectuarConsulta("SELECT password_usuario FROM usuario WHERE id_usuario = '$idUsuario'");
        $fila = $resultado->fetch_assoc();

        if ($fila && password_verify($passwordActual, $fila['password_usuario'])) {
            // Contraseña actual correcta → actualizar
            $nuevaHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $consultaBase .= ", password_usuario = '$nuevaHash'";
        } else {
            // Contraseña incorrecta 
            echo "incorrecta";
            $mysql->desconectar();
            exit();
        }
    }

   
    $consultaBase .= " WHERE id_usuario = '$idUsuario'";
    $mysql->efectuarConsulta($consultaBase);

    
    $_SESSION['nombre_usuario'] = $nuevoNombre;
    $_SESSION['apellido_usuario'] = $nuevoApellido;
    $_SESSION['email_usuario'] = $nuevoEmail;

    $mysql->desconectar();

    // Devolver respuesta "ok" para que el JS lo maneje
    echo "ok";
    exit();
}
?>



