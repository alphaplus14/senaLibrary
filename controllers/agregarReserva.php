<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");
session_start();

if (!isset($_SESSION['tipo_usuario'])) { 
  echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
  exit();
}

require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libros'])) {
    $libros = json_decode($_POST['libros'], true);

    if (empty($libros)) {
        echo json_encode(['success' => false, 'message' => 'No se enviaron libros.']);
        exit;
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $idUsuario = $_SESSION['id_usuario']; 
    $queryReserva = "INSERT INTO reserva (fk_usuario, fecha_reserva, estado_reserva) 
                     VALUES ('$idUsuario', NOW(), 'Pendiente')";
    $mysql->efectuarConsulta($queryReserva);

    // Obtener el ID de la reserva creada
    $resultId = $mysql->efectuarConsulta("SELECT MAX(id_reserva) AS id FROM reserva");
    $rowId = mysqli_fetch_assoc($resultId);
    $idReserva = $rowId['id'];

    $errores = [];

    foreach ($libros as $lib) {
        $idLibro = isset($lib['id']) ? intval($lib['id']) : 0;

        if ($idLibro > 0) {
            $queryDetalle = "INSERT INTO reserva_has_libro (reserva_id_reserva, libro_id_libro) 
                             VALUES ('$idReserva', '$idLibro')";
            if (!$mysql->efectuarConsulta($queryDetalle)) {
                $errores[] = "Error con el libro ID $idLibro";
            }

            // Restar del stock
            $queryStock = "UPDATE libro SET cantidad_libro = cantidad_libro - 1 WHERE id_libro = $idLibro";
            $mysql->efectuarConsulta($queryStock);
        }
    }

    $mysql->desconectar();

    if (count($errores) === 0) {
        echo json_encode(['success' => true, 'message' => 'Reserva registrada correctamente', 'id_reserva' => $idReserva]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Algunos libros no se registraron correctamente', 'errores' => $errores]);
    }

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
}
?>
