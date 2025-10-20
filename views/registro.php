<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de usuario</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      crossorigin="anonymous"
    />
  </head>
  <body class="bg-light">
    <div class="container-fluid vh-100">
      <div class="row h-100 d-flex justify-content-center align-items-center">
        <div class="col-10 col-sm-6 col-md-4 col-lg-3 bg-white p-4 rounded shadow">
          <h2 class="text-center mb-4">Crear cuenta</h2>
<?php if(isset($_GET['error']) && $_GET['error'] == 'correo'): ?>
  <div class="alert alert-danger text-center">El correo ya está registrado.</div>
<?php endif; ?>

<?php if(isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
  <div class="alert alert-success text-center">Registro exitoso. Ahora puedes iniciar sesión.</div>
<?php endif; ?>

          <form action="../controllers/registro.php" method="POST">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre:</label>
              <input type="text" class="form-control" name="nombre" placeholder="Ingrese su nombre" required />
            </div>
            <div class="mb-3">
              <label for="apellido" class="form-label">Apellido:</label>
              <input type="text" class="form-control" name="apellido" placeholder="Ingrese su apellido" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico:</label>
              <input type="email" class="form-control" name="email" placeholder="Ingrese su correo" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña:</label>
              <input type="password" class="form-control" name="password" placeholder="Cree una contraseña" required />
            </div>
            <div class="d-grid">
              <button type="submit" name="registrar" class="btn btn-success">Registrarse</button>
            </div>

            <div class="text-center mt-3">
              <p>¿Ya tienes cuenta? 
                <a href="login.php" class="text-success fw-bold text-decoration-none">Inicia sesión</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>

