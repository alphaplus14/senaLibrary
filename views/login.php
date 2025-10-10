<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de sesión</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    />
  </head>
  <body class="bg-light">
    <div class="container-fluid vh-100">
      <div class="row h-100 d-flex justify-content-center align-items-center">
        <div class="col-10 col-sm-6 col-md-4 col-lg-3 bg-white p-4 rounded shadow">
          <h2 class="text-center mb-4">Inicio de sesión</h2>
          <form action="../controllers/login.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Email:</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electronico" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña:</label>
              <input type="password" class="form-control" name="password" placeholder="Digite la contraseña" required />
            </div>
            <div class="d-grid">
              <button type="submit" name="enviar" class="btn btn-success">Acceder</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
