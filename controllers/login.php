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
    $mysql->desconectar();

    $usuarios = mysqli_fetch_assoc($resultado);

        if ($usuarios) {

        // Verificar estado
        if ($usuarios['estado'] === 'Inactivo') {
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Usuario inactivo',
                    text: 'No tiene permiso de acceso.'
                }).then(() => {
                    window.location = '../views/login.php';
                });
                </script>
            </body>
            </html>";
            exit();
        }



        // Verificar contraseña
        if (password_verify($password, $usuarios['password_usuario'])) {
            // Guardar sesión
           $_SESSION['id_usuario'] = $usuarios['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuarios['nombre_usuario'];
            $_SESSION['apellido_usuario'] = $usuarios['apellido_usuario'];
            $_SESSION['email_usuario'] = $usuarios['email_usuario'];
            $_SESSION['tipo_usuario'] = $usuarios['tipo_usuario'];

            // Redirigir al dashboard
           echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Bienvenido',
                    text: 'Hola, ".$usuarios['nombre_usuario']."',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location = '../index.php';
                });
                </script>
            </body>
            </html>";
            exit();
        } else {
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    text: 'Por favor, inténtalo nuevamente.'
                }).then(() => {
                    window.location = '../views/login.php';
                });
                </script>
            </body>
            </html>";
            exit();
        }

    } else {
         echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Usuario no encontrado',
                text: 'Verifica el correo e inténtalo de nuevo.'
            }).then(() => {
                window.location = '../views/login.php';
            });
            </script>
        </body>
        </html>";
        exit();
    }

} else {

    header("Location: ../views/login.php");
    exit();
}
?>