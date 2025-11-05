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

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libros'])) {
        $libros = json_decode($_POST['libros'], true);

        if (empty($libros)) {
            throw new Exception('No se enviaron libros.');
        }

        $mysql = new MySQL();
        $mysql->conectar();

        // Crear reserva
        $estadoReserva = "Pendiente";
        $idUsuario = intval($_SESSION['id_usuario']);

        $queryReserva = "
            INSERT INTO reserva (fk_usuario, fecha_reserva, estado_reserva) 
            VALUES ($idUsuario, NOW(), '$estadoReserva')
        ";
        $resultadoReserva = $mysql->efectuarConsulta($queryReserva);

        if (!$resultadoReserva) {
            throw new Exception('Error al registrar la reserva.');
        }

        // Obtener el ID de la ultima creada, esto sirve si se crean simultaneamente
        $resultId = $mysql->efectuarConsulta("SELECT LAST_INSERT_ID() AS id");
        $rowId = mysqli_fetch_assoc($resultId);
        $idReserva = $rowId['id'];

        if (!$idReserva) {
            throw new Exception('No se pudo obtener el ID de la reserva.');
        }

        // Registrar los libros
        $errores = [];
        foreach ($libros as $lib) {
            $idLibro = isset($lib['id']) ? intval($lib['id']) : 0;
            if ($idLibro > 0) {
                $queryDetalle = "
                    INSERT INTO reserva_has_libro (reserva_id_reserva, libro_id_libro)
                    VALUES ($idReserva, $idLibro)
                ";
                if (!$mysql->efectuarConsulta($queryDetalle)) {
                    $errores[] = "Error con el libro ID $idLibro";
                }
            } else {
                $errores[] = "ID de libro inválido.";
            }
        }

        $mysql->desconectar();

        if (count($errores) === 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Reserva registrada correctamente.',
                'id_reserva' => $idReserva
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Algunos libros no se registraron correctamente.',
                'errores' => $errores
            ]);
        }
    } else {
        throw new Exception('Faltan datos en la solicitud.');
    }

} catch (Exception $e) {
    if (isset($mysql)) {
        @$mysql->desconectar();
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
