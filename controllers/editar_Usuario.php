<?php 
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['tipo_usuario'])){
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

// Verificar si el correo ya está registrado
    $consultaExiste = "SELECT id_usuario FROM usuario WHERE email_usuario = '$email'";
    $resultado = $mysql->efectuarConsulta($consultaExiste);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
        $mysql->desconectar();
        exit;
    }

//si cambia la contraseña 
if (!empty($passwordNueva)) {

    //validar la contraseña vieja 
    if (!password_verify($passwordOld, $row['password'])) {
        echo json_encode(["success" => false, "message" => "La contraseña actual no coincide"]);
        exit();
    }
//si coincide actuliza por la nueva
    $passwordNuevaHash = password_hash($passwordNueva, PASSWORD_BCRYPT);
    $consulta = "UPDATE usuario
        SET nombre_usuario='$nombre',
            apellido_usuario='$apellido',
            email_usuario='$correo',
            password_usuario='$passwordNuevaHash',
            tipo_usuario='$cargo'
        WHERE id_usuario='$id'";
} else {
    //si no actualiza queda la misma

    $consulta = "UPDATE usuario 
        SET nombre_usuario='$nombre',
            apellido_usuario='$apellido',
            email_usuario='$correo',
            tipo_usuario='$cargo'
        WHERE id_usuario='$id'";
}

$result = $mysql->efectuarConsulta($consulta);

if ($result === true) {
    echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar"]);
}

$mysql->desconectar();
