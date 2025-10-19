<?php 
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['cargo'])){
  echo json_encode(["success" => false, "message" => "Sesión no válida"]);
  exit();
}
//conexion 
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit();
}


$nombre        = $_POST['nombre'];
$apellido      =$_POST['apellido'];
$correo        = $_POST['correo'];
$cargo         = $_POST['cargo'];
$passwordOld   = $_POST['passwordOld']; 
$passwordNueva = $_POST['passwordNueva'];




if (!empty($passwordNueva)) {

    //validar la contraseña vieja 
    if (!password_verify($passwordOld, $row['password'])) {
        echo json_encode(["success" => false, "message" => "La contraseña actual no coincide"]);
        exit();
    }
//si coincide actuliza por la nueva
    $passwordNuevaHash = password_hash($passwordNueva, PASSWORD_BCRYPT);
    $consulta = "UPDATE empleados 
        SET nombre='$nombre',
            password='$passwordNuevaHash',
            cargo_id='$cargo', 
        WHERE id='$id'";
} else {
    //si no actualiza queda la misma

    $consulta = "UPDATE empleados 
        SET nombre='$nombre',
            cargo_id='$cargo', 
            telefono='$telefono'
        WHERE id='$id'";
}

$result = $mysql->efectuarConsulta($consulta);

if ($result === true) {
    echo json_encode(["success" => true, "message" => "Empleado actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$mysql->desconectar();
