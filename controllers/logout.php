<?php
// Se inicia la sesión para poder cerrarla correctamente
session_start();

// Se eliminan todas las variables de la sesión
session_unset();

// Se destruye completamente la sesión actual
session_destroy();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
// Se redirige al login tras cerrar la sesión
header("Location: ../views/login.php");
exit();
?>