<?php
session_start();

// Verificamos que haya sesión iniciada
if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow p-4">
    <h3 class="mb-4">Perfil del Usuario</h3>

    <div id="perfil">
      <p><strong>Nombre:</strong> <span id="nombre"></span></p>
      <p><strong>Apellido:</strong> <span id="apellido"></span></p>
      <p><strong>Email:</strong> <span id="email"></span></p>
      <p><strong>Tipo de Usuario:</strong> <span id="tipo"></span></p>
      <p><strong>Estado:</strong> <span id="estado"></span></p>
    </div>
  </div>
</div>

<script>
  // Al cargar la página, obtenemos los datos del usuario
  document.addEventListener('DOMContentLoaded', () => {
    fetch('../controllers/obtenerUsuario.php')
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('nombre').textContent = data.data.nombre_usuario;
          document.getElementById('apellido').textContent = data.data.apellido_usuario;
          document.getElementById('email').textContent = data.data.email_usuario;
          document.getElementById('tipo').textContent = data.data.tipo_usuario;
          document.getElementById('estado').textContent = data.data.estado;
        } else {
          document.getElementById('perfil').innerHTML = `<p class="text-danger">${data.message}</p>`;
        }
      })
      .catch(err => {
        console.error('Error:', err);
        document.getElementById('perfil').innerHTML = '<p class="text-danger">Error al cargar el perfil.</p>';
      });
  });
</script>

</body>
</html>
