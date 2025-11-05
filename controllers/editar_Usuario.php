<?php 
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

session_start();

if (!isset($_SESSION['tipo_usuario'])){
  echo json_encode(["success" => false, "message" => "Sesión no válida"]);
  exit();
}

// Conexión 
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit();
}

// Sanitizar entradas
$nombre        = addslashes($_POST['nombre']);
$apellido      = addslashes($_POST['apellido']);
$correo        = addslashes($_POST['correo']);
$cargo         = addslashes($_POST['cargo']);
$passwordOld   = addslashes($_POST['passwordOld']);
$passwordNueva = addslashes($_POST['passwordNueva']); 

// Verificar si el correo ya está registrado por otro usuario
$consultaExiste = "SELECT id_usuario FROM usuario 
                   WHERE email_usuario = '$correo' 
                   AND id_usuario != '$id'";
$resultado = $mysql->efectuarConsulta($consultaExiste);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado por otro usuario.']);
    $mysql->desconectar();
    exit;
}

// Si cambia la contraseña 
if (!empty($passwordNueva)) {

    // Validar la contraseña vieja 
    $consultaPassword = "SELECT password_usuario FROM usuario WHERE id_usuario = '$id'";
    $resultadoPass = $mysql->efectuarConsulta($consultaPassword);
    $row = mysqli_fetch_assoc($resultadoPass);

    if (!$row || !password_verify($passwordOld, $row['password_usuario'])) {
        echo json_encode(["success" => false, "message" => "La contraseña actual no coincide"]);
        exit();
    }

    // Si coincide actualiza por la nueva
    $passwordNuevaHash = password_hash($passwordNueva, PASSWORD_BCRYPT);
    $consulta = "UPDATE usuario
        SET nombre_usuario='$nombre',
            apellido_usuario='$apellido',
            email_usuario='$correo',
            password_usuario='$passwordNuevaHash',
            tipo_usuario='$cargo'
        WHERE id_usuario='$id'";

} else {
    // Si no cambia la contraseña, solo actualiza los demás campos
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
?> 
