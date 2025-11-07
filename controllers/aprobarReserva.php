<?php
require_once '../models/MySQL.php';
require_once '../controllers/emailService.php'; 
session_start();

header('Content-Type: application/json');
date_default_timezone_set('America/Bogota');

try {

    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'Administrador') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit();
    }

    if (!isset($_POST['id_reserva']) || !isset($_POST['dias_prestamo'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit();
    }

    $idReserva = intval($_POST['id_reserva']);
    $diasPrestamo = intval($_POST['dias_prestamo']);

   // Calcular fechas
    $fechaPrestamo = date('Y-m-d');
    $fechaDevolucion = date('Y-m-d', strtotime($fechaPrestamo . ' + ' . $diasPrestamo . ' days'));

    $mysql = new MySQL();
    $mysql->conectar();

    //  Verificar estado de la reserva
    $reserva = $mysql->efectuarConsulta("SELECT fk_usuario, estado_reserva FROM reserva WHERE id_reserva = $idReserva");
    $res = mysqli_fetch_assoc($reserva);

    if (!$res || $res['estado_reserva'] != 'Pendiente') {
        echo json_encode(['success' => false, 'message' => 'La reserva ya fue procesada']);
        exit();
    }

    //capturo en una variable el id del usuario
    $idUsuario = $res['fk_usuario'];

    // Traer libros de la reserva
    $libros = $mysql->efectuarConsulta(" SELECT libro.id_libro, libro.titulo_libro, libro.cantidad_libro
        FROM reserva_has_libro
        INNER JOIN libro ON libro.id_libro = reserva_has_libro.libro_id_libro
        WHERE reserva_id_reserva = $idReserva
    ");

    //  Verificar stock
    while ($row = mysqli_fetch_assoc($libros)) {
        if ($row['cantidad_libro'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'No hay stock disponible para: ' . $row['titulo_libro']]);
            exit();
        }
    }

    //  Restar stock
    $mysql->efectuarConsulta("UPDATE libro 
        INNER JOIN reserva_has_libro ON libro.id_libro = reserva_has_libro.libro_id_libro
        SET libro.cantidad_libro = libro.cantidad_libro - 1
        WHERE reserva_has_libro.reserva_id_reserva = $idReserva
    ");

    //  Cambiar estado de la reserva
    $mysql->efectuarConsulta("UPDATE reserva SET estado_reserva='Aprobada' WHERE id_reserva=$idReserva");

    //  Registrar prestamo
    $mysql->efectuarConsulta("INSERT INTO prestamo (fk_reserva, fecha_prestamo, fecha_devolucion_prestamo) VALUES ($idReserva, '$fechaPrestamo','$fechaDevolucion')");

    //  Obtener correo del usuario
    $usuario = $mysql->efectuarConsulta("SELECT email_usuario, nombre_usuario FROM usuario WHERE id_usuario = $idUsuario
");
    $usuario = mysqli_fetch_assoc($usuario);

    // Enviar correo
    $asunto = " Reserva Aprobada - SenaLibrary";
    $mensaje = "
        <h3>¡Tu reserva ha sido aprobada!</h3>
        <p>Hola <strong>{$usuario['nombre_usuario']}</strong>, tu reserva #$idReserva ha sido aprobada.</p>
        <p>Puedes acercarte a recoger tus libros en la fecha correspondiente.</p>
        <br><strong>SenaLibrary</strong>
    ";

    enviarCorreo($usuario['email_usuario'], $asunto, $mensaje);

    echo json_encode(['success' => true, 'message' => 'Reserva aprobada correctamente y correo enviado']);

    $mysql->desconectar();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
