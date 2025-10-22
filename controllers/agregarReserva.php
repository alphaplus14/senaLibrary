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
$mysql = new MySQL();
$mysql->conectar();

$idUsuario = $_SESSION['id_usuario'];
if (isset($_POST['id_libro'])) {
    $idLibro = intval($_POST['id_libro']);
}

if ($idLibro <= 0) {
  echo json_encode(['success' => false, 'message' => 'Libro no válido']);
  exit();
}

// Verificar disponibilidad del libro
$consulta = $mysql->efectuarConsulta("SELECT cantidad_libro FROM libro WHERE id_libro = $idLibro");
$fila = mysqli_fetch_assoc($consulta);

if (!$fila || $fila['cantidad_libro'] <= 0) {
  echo json_encode(['success' => false, 'message' => 'No hay ejemplares disponibles']);
  $mysql->desconectar();
  exit();
}

// Crear una nueva reserva
$fecha = date("Y-m-d H:i:s");
$estado = 'Pendiente';

$insertReserva = $mysql->efectuarConsulta("
  INSERT INTO reserva (fk_usuario, fecha_reserva, estado_reserva)
  VALUES ($idUsuario, '$fecha', '$estado')
");

if (!$insertReserva) {
  echo json_encode(['success' => false, 'message' => 'Error al crear la reserva']);
  $mysql->desconectar();
  exit();
}

// Consultar el ID de la reserva creada
$consultaId = $mysql->efectuarConsulta("
  SELECT id_reserva 
  FROM reserva 
  WHERE fk_usuario = '$idUsuario' 
  ORDER BY id_reserva DESC 
  LIMIT 1
");

if ($consultaId && $consultaId->num_rows > 0) {
  $row = $consultaId->fetch_assoc();
  $idReserva = $row['id_reserva'];

  // Asociar el libro con la reserva
  $insertDetalle = $mysql->efectuarConsulta("
    INSERT INTO reserva_has_libro (fk_reserva, fk_libro)
    VALUES ($idReserva, $idLibro)
  ");

  if (!$insertDetalle) {
    echo json_encode(['success' => false, 'message' => 'Error al vincular el libro']);
    $mysql->desconectar();
    exit();
  }

  //Actualizar cantidad del libro
  $mysql->efectuarConsulta("
    UPDATE libro SET cantidad_libro = cantidad_libro - 1 WHERE id_libro = $idLibro
  ");

  echo json_encode(['success' => true, 'message' => 'Libro agregado correctamente a tu reserva.']);
} else {
  echo json_encode(['success' => false, 'message' => 'No se pudo obtener el ID de la reserva']);
}

$mysql->desconectar();
?>
