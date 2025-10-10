<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../models/MySQL.php';
session_start();


if (isset($_POST['email']) && !empty($_POST['email']) && 
    isset($_POST['password']) && !empty($_POST['password'])) {

    $mysql = new MySQL();
    $mysql->conectar();

    // Sanitizacion de datos
    $correo = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // Consulta del usuario
    $resultado = $mysql->efectuarConsulta("SELECT * FROM usuario WHERE email_usuario='".$correo."'");

    if ($usuarios = mysqli_fetch_assoc($resultado)) {



        // Verificar contraseña
        //?se comenta temporalmente el password verify para probar login con contraseña en texto plano
        //!! if (password_verify($password, $usuarios['password'])) {
        if ($password === $usuarios['password_usuario']) {
            // Guardar sesión
$_SESSION['usuario_id'] = $usuarios['id_usuario'];
$_SESSION['nombre_usuario'] = $usuarios['nombre_usuario'];
$_SESSION['apellido_usuario'] = $usuarios['apellido_usuario'];
$_SESSION['email_usuario'] = $usuarios['email_usuario'];
$_SESSION['tipo_usuario'] = $usuarios['tipo_usuario'];

            // Redirigir al dashboard
            header("Location: ../index.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }

    } else {
        echo "correo no encontrado";
    }
$mysql->desconectar();
} else {

    header("Location: ../views/login.php");
    exit();
}
?>