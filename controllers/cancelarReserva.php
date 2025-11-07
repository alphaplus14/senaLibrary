<?php
header('Content-Type: application/json');
require_once '../models/MySQL.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);

    $mysql = new MySQL();
    $mysql->conectar();

    // Verificar que la reserva exista
    $queryVerificar = "SELECT id_reserva FROM reserva WHERE id_reserva = $idReserva";
    $resultadoVerificar = $mysql->efectuarConsulta($queryVerificar);

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        
        // obtener los libros de la reserva
        $queryLibros = "
            SELECT libro_id_libro 
            FROM reserva_has_libro 
            WHERE reserva_id_reserva = $idReserva
        ";
        $resultadoLibros = $mysql->efectuarConsulta($queryLibros);

        // devolver el stock

        $errores = [];
        while ($fila = mysqli_fetch_assoc($resultadoLibros)) {
            $idLibro = intval($fila['libro_id_libro']);
            
            // Sumar 1 al stock
            $queryActualizarStock = "
                UPDATE libro 
                SET cantidad_libro = cantidad_libro + 1 
                WHERE id_libro = $idLibro
            ";
            
            if (!$mysql->efectuarConsulta($queryActualizarStock)) {
                $errores[] = "Error al devolver stock del libro ID $idLibro";
            }
            
            // actualizar disponibilidad si no habia libros
            $queryActualizarDisponibilidad = "
                UPDATE libro 
                SET disponibilidad_libro = 'Disponible' 
                WHERE id_libro = $idLibro AND cantidad_libro > 0
            ";
            $mysql->efectuarConsulta($queryActualizarDisponibilidad);
        }

        // Cambiar el estado de la reserva a Cancelada
        $queryCancelar = "UPDATE reserva SET estado_reserva = 'Cancelada' WHERE id_reserva = $idReserva";
        $resultadoCancelar = $mysql->efectuarConsulta($queryCancelar);

        $mysql->desconectar();

        if ($resultadoCancelar) {
            if (count($errores) === 0) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Reserva cancelada correctamente y stock devuelto'
                ]);
            } else {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Reserva cancelada pero hubo errores al devolver algunos libros',
                    'errores' => $errores
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cancelar la reserva']);
        }
    } else {
        $mysql->desconectar();
        echo json_encode(['success' => false, 'message' => 'La reserva no existe']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
}
?>