<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function enviarCorreo($destinatario, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''; //aqui ira el correo, recomiendo crear uno solo para que funcione con senalibrary
        $mail->Password = ''; // cesar, la contraseña que de google debe ir aqui sin espacios
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Evitar errores SSL locales
        $mail->SMTPOptions = [
          'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          ]
        ];

        // Destinatario
        $mail->setFrom('aqui va el correo cesar', 'SenaLibrary');
        $mail->addAddress($destinatario);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

