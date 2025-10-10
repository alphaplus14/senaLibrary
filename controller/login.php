<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../modelo/MySQL.php';
session_start();


if (isset($_POST['cedula']) && !empty($_POST['cedula']) && 
    isset($_POST['password']) && !empty($_POST['password'])) {

    $mysql = new MySQL();
    $mysql->conectar();

    // Sanitizacion de datos
    $identificacion = htmlspecialchars(trim($_POST['cedula']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // Consulta del usuario
    $resultado = $mysql->efectuarConsulta("SELECT * FROM empleados WHERE identificacion='".$identificacion."'");

    if ($usuarios = mysqli_fetch_assoc($resultado)) {

        // Verificar estado
        if ($usuarios['estado'] === 'Inactivo') {
            echo "Usuario inactivo, no tiene permiso de acceso.";
            
            header("Location: ../views/login.php");
            exit();
        }

        // Verificar contraseña
        if (password_verify($password, $usuarios['password'])) {
            // Guardar sesión
            $_SESSION['usuario_id'] = $usuarios['id'];
            $_SESSION['identificacion'] = $usuarios['identificacion'];
            $_SESSION['cargo'] = $usuarios['cargo_id'];
            $_SESSION['nombre_usuario']=$usuarios['nombre'];
            $_SESSION['fotoPerfil']=$usuarios['foto'];
            $_SESSION['fecha']=$usuarios['fecha'];

            // Redirigir al dashboard
            header("Location: ../index.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }

    } else {
        echo "Usuario no encontrado";
    }
$mysql->desconectar();
} else {

    header("Location: ../views/login.php");
    exit();
}
?>