<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//conexion a la base de datos
require_once 'models/MySQL.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    header("location: ./views/login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();
$idUsuario=$_SESSION['id_usuario'];
$rol= $_SESSION['tipo_usuario'];
$nombre=$_SESSION['nombre_usuario'];

$mysql = new MySQL();
$mysql->conectar();
//consulta para obtener los usuarios
$resultado=$mysql->efectuarConsulta("SELECT * FROM usuario");
$resultadolibros=$mysql->efectuarConsulta("SELECT * FROM libro");

?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> SenaLibrary </title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- Estilo propio -->
     <link rel="stylesheet" href="./css/style.css">

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- esto es para que funcione chars.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables + Bootstrap -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables núcleo -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<!-- Integración Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- Extensión Responsive (versión compatible 2.5.0) -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- Extensión Column Control (si de verdad la usas) -->
<link href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




  <style>
.container-documentos {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;           /* ✅ para que sea responsive */
  gap: 30px;                 /* espacio entre columnas */
  margin: 40px auto;
  max-width: 1400px;
  padding: 20px;
}

.card-documento {
  flex: 1 1 30%;             /* ✅ tres columnas iguales */
  min-width: 320px;          /* ancho mínimo para pantallas pequeñas */
  background-color: #ffffff;
  padding: 30px 35px;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}


.card-documento:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
}

.titulo-seccion {
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 25px;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-documentos {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.row-form {
  display: flex;
  flex-direction: column; /* ✅ Fuerza disposición vertical */
  gap: 16px;
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-width: 180px;
}

.form-group label {
  font-weight: 600;
  color: #334155;
  margin-bottom: 5px;
}

.form-group input[type="date"],
.form-group select {
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 8px 10px;
  background-color: #f8fafc;
  color: #0f172a;
  transition: all 0.3s ease;
}

.form-group input[type="date"]:focus,
.form-group select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 6px rgba(37, 99, 235, 0.3);
  outline: none;
}

.btn-generar {
  background-color: #b70404dd;
  color: #ffffff;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  padding: 12px 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  cursor: pointer;
  align-self: flex-start; /* alinea a la izquierda */
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-generar:hover {
  background-color: #ff0000ff;
  transform: translateY(-2px);
}

.btn-generar i {
  font-size: 16px;
}

.card-documento {
  min-height: 450px; 
}

.btn-group {
  display: flex;
  gap: 10px;
  align-items: center;
}

/* ... dentro de <style> ... */

/* Modificación a .btn-group para alinear los botones */
.btn-group {
    display: flex;
    gap: 15px; /* Aumenta el espacio entre botones */
    align-items: center;
    /* Nuevo: Añade esto para que los botones crezcan y se repartan el espacio */
    width: 100%; 
}

/* Ajustes al botón de Excel para que se vea igual que el de PDF */
.btn-excel {
    background-color: #28a745;
    color: #fff;
    font-weight: 600; /* Asegura el mismo peso de fuente */
    border: none;
    border-radius: 8px; /* Usa el mismo radio que .btn-generar */
    padding: 12px 18px; /* Usa el mismo padding que .btn-generar */
    text-decoration: none;
    display: inline-flex; /* Para alinear icono y texto */
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    
    /* CLAVE: Hace que el botón ocupe el espacio disponible de forma equitativa */
    flex-grow: 1; 
}

.btn-excel:hover {
    background-color: #218838;
    transform: translateY(-2px);
    color: #fff;
}

/* Asegura que el botón de PDF también crezca equitativamente en un grupo */
.btn-group .btn-generar {
    flex-grow: 1; 
    margin-top: 0; /* Anula cualquier margen que pueda tener */
    align-self: unset; /* Anula align-self: flex-start; del estilo anterior */
}

/* ... otras clases CSS ... */

</style>











<!-- script de los graficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/graficos_libro.js"></script>





  </head>
  <!--end::Head-->
  <!--begin::Body-->




























  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="index.php" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="index.php" class="nav-link">Inicio</a>
            </li>
            
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
  <a href="#" class="nav-link dropdown-toggle text-white fw-semibold" data-bs-toggle="dropdown">
    <span class="d-none d-md-inline"><?php echo $nombre; ?></span>
  </a>

  <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
    <!-- Cabecera del usuario -->
    <li class="bg-primary text-white text-center rounded-top py-3">
      <p class="mb-0 fw-bold fs-5"><?php echo $nombre; ?></p>
      <small><?php echo $rol; ?></small>
    </li>

    <!-- Separador -->
    <li><hr class="dropdown-divider m-0"></li>

    <!-- Opciones del menu -->
    <li>
      <a href="./views/perfilUsuario.php" class="dropdown-item d-flex align-items-center py-2">
        <i class="bi bi-person me-2 text-secondary"></i> Perfil
      </a>
    </li>

    <!-- Separador -->
    <li><hr class="dropdown-divider m-0"></li>

    <!-- Opción de cerrar sesión -->
    <li>
      <a href="./controllers/logout.php" class="dropdown-item d-flex align-items-center text-danger py-2">
        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
      </a>
    </li>
  </ul>
</li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar verde shadow">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.php" class="brand-link">
            <!--begin::Brand Image-->
           
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="title"> SenaLibrary</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="./index.php" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer me-2"></i>
                  <span>
                    Dashboard
                  </span>
                  </a>
              
               <?php if ($rol == 'Administrador'): ?>
              <li class="nav-item">
                <a href="./views/documentos.php" class="nav-link">
                  <i class="bi bi-file-earmark-pdf me-2"> </i>    
                  <span>
                   Documentos 
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/inventario.php" class="nav-link">
                 <i class="bi bi-box-seam me-2"> </i>
                  <span> Inventario </span>
                </a>
              </li>
              <?php endif; ?>
               <?php if ($rol == 'Invitado'): ?>
              <li class="nav-item">
                <a href="./views/gestionarReserva.php" class="nav-link">
                 <i class="bi bi-calendar-check me-2"> </i>
                  <span> Gestionar Reserva </span>
                </a>
              </li>
              <?php endif; ?>

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <!-- vista de diferentes usuarios  -->
            <div class="row">
              <?php if($rol == "Administrador"): ?>
              <div class="col-sm-6">
                <h3 class="mb-0">Lista de Clientes</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Lista de Usuarios</li>
                </ol>
              </div>
              <?php endif; ?>
            <?php if($rol != "Administrador"): ?>
            <div class="col-sm-6">
                <h3 class="mb-0">Lista de Libros</h3>
            </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Lista de Libros</li>
                </ol>
              </div>
            <?php endif; ?>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">

            <?php if ($rol == 'Administrador'): ?>
              <div class="row mb-3 align-items-center">
                <div class="col-md-6 d-flex gap-2">
                  <button type="button" class="btn btn-success" onclick="agregarUsuario()">
                    ➕ Agregar Nuevo Usuario
                  </button>
                </div>
              </div>
            <?php endif; ?>

            <div class="row">
              <?php if($rol == "Administrador"): ?>
                <div class="table-responsive mb-5">
                  <table id="tablaUsuarios" class="table table-striped table-bordered" width="100%">
                    <thead class="table-success">
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo Electrónico</th>
                        <th>Cargo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                          <td><?= $fila['id_usuario'] ?></td>
                          <td><?= $fila['nombre_usuario'] ?></td>
                          <td><?= $fila['apellido_usuario'] ?></td>
                          <td><?= $fila['email_usuario'] ?></td>
                          <td><?= $fila['tipo_usuario'] ?></td>
                          <td><?= $fila['estado'] ?></td>
                          <td class="text-center">
                            <a class="btn btn-warning btn-sm" title="Editar" onclick="editarUsuario(<?= $fila['id_usuario'] ?>)">
                              <i class="bi bi-pencil-square"></i>
                            </a>
                            |
                            <a class="btn btn-danger btn-sm" href="javascript:void(0);" onclick="eliminarEmpleado(<?= $fila['id_usuario'] ?>)" title="Eliminar">
                              <i class="bi bi-trash"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>

            <?php if($rol != "Administrador"): ?>
              <div class="table-responsive">
                  <div class="col"> 
                      <button class="btn btn-sm btn-primary btnReservar mb-4 w-100" onclick="abrirCrearReserva()">
                          <i class="bi bi-bookmark-plus"></i> Realizar Reserva
                      </button> 
                  </div>
                  
                  <table id="tablaLibros" class="table table-striped table-bordered" width="100%">
                      <thead class="table-success">
                          <tr>
                              <th>ID</th>
                              <th>Título</th>
                              <th>Autor</th>
                              <th>ISBN</th>
                              <th>Categoría</th>
                              <th>Cantidad</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php while($fila = $resultadolibros->fetch_assoc()): ?>
                              <tr>
                                  <td><?= $fila['id_libro'] ?></td>
                                  <td><?= $fila['titulo_libro'] ?></td>
                                  <td><?= $fila['autor_libro'] ?></td>
                                  <td><?= $fila['ISBN_libro'] ?></td>
                                  <td><?= $fila['categoria_libro'] ?></td>
                                  <td><?= $fila['cantidad_libro'] ?></td>
                                  <td>
                                      <?php if($fila['cantidad_libro'] == 0): ?>
                                          <span class="badge bg-danger">No disponible</span>
                                      <?php else: ?>
                                          <span class="badge bg-success"><?= $fila['disponibilidad_libro'] ?></span>
                                      <?php endif; ?>
                                  </td>
                              </tr>
                          <?php endwhile; ?>
                      </tbody>
                  </table>
              </div>
            <?php endif; ?>
            </div>
          </div>
        </div>

        
<div class="card-grafico">
  <h4 class="titulo-seccion"><i class="fa-solid fa-book"></i> Total de libros registrados</h4>
  <canvas id="graficoTotalLibros" width="400" height="200"></canvas>
</div>





<?php
$hoy = date('Y-m-d');
$inicioMes = date('Y-m-01');
?>
<?php if ($rol == 'Administrador'): ?>
<!-- === FORMULARIOS DE DOCUMENTOS === -->
<div class="container-documentos">

  <!-- === PDF DE RESERVAS === -->
  <div class="card-documento">
    <h4 class="titulo-seccion">
      <i class="fa-solid fa-calendar-check"></i> REPORTE DE LAS RESERVAS:
    </h4>
    <form action="views/generar_pdf_reservas.php" target="_blank" method="get" class="form-documentos">
      <div class="row-form">
        <div class="form-group">
          <label for="fechaInicio">Fecha inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" required value="<?php echo htmlspecialchars($inicioMes); ?>">
        </div>

        <div class="form-group">
          <label for="fechaFin">Fecha fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" required value="<?php echo htmlspecialchars($hoy); ?>">
        </div>

        <div class="form-group">
          <label for="salida">Ver:</label>
          <select id="salida" name="salida">
            <option value="I">Ver en el navegador</option>
            <option value="D">Descargar</option>
          </select>
        </div>

        <div class="form-group">
<div style="text-align: center;">
  <button type="submit" class="btn-generar">
    <i class="fa-solid fa-file-pdf"></i> GENERAR PDF
  </button>
</div>
        </div>
        
      </div>
    </form>
  </div>

  <!-- === PDF DE INVENTARIO === -->
<div class="card-documento">
    <h4 class="titulo-seccion">
        <i class="fa-solid fa-boxes-stacked"></i> REPORTE DEL INVENTARIO:
    </h4>
    <form action="views/generar_pdf_inventario.php" target="_blank" method="get" class="form-documentos">
        <div class="row-form">
            <div class="form-group">
                <label for="salida_inventario">Ver:</label>
                <select id="salida_inventario" name="salida"> <option value="I">Ver en el navegador</option>
                    <option value="D">Descargar</option>
                </select>
            </div>

            <div class="form-group btn-group">


          <button type="submit" class="btn-generar">
            <i class="fa-solid fa-file-pdf"></i> GENERAR PDF  
          </button>
                <a href="views/generar_excel_inventario.php" class="btn-excel">
                    <i class="fa-solid fa-file-excel"></i>  EXCEL
                </a>
            </div>
        </div>
    </form>
</div>

  <!-- === PDF DE PRÉSTAMOS === -->
  <div class="card-documento">
    <h4 class="titulo-seccion">
      <i class="fa-solid fa-handshake"></i> REPORTE DE LOS PRÉSTAMOS:
    </h4>
    <form action="views/generar_pdf_prestamos.php" target="_blank" method="get" class="form-documentos">
      <div class="row-form">
        <div class="form-group">
          <label for="fechaInicio">Fecha inicio:</label>
          <input type="date" id="fechaInicio" name="fechaInicio" required value="<?php echo htmlspecialchars($inicioMes); ?>">
        </div>

        <div class="form-group">
          <label for="fechaFin">Fecha fin:</label>
          <input type="date" id="fechaFin" name="fechaFin" required value="<?php echo htmlspecialchars($hoy); ?>">
        </div>

        <div class="form-group">
          <label for="salida">Ver:</label>
          <select id="salida" name="salida">
            <option value="I">Ver en el navegador</option>
            <option value="D">Descargar</option>
          </select>
        </div>

        <div class="form-group btn-group">
          <button type="submit"  class="btn-generar">
            <i class="fa-solid fa-file-pdf"></i> GENERAR PDF  
          </button>
<button type="submit" formaction="views/generar_excel_prestamos.php" class="btn-excel">
  <i class="fa-solid fa-file-excel"></i> EXCEL
</button>
        </div>
      </div>
    </form>
  </div>

</div>
<?php endif; ?>


















        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">SenaLibrary</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="public/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>
<script>
$(document).ready(function() {
   $('#tablaEmpleados').DataTable({
    language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    },
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    responsive: true,
    autoWidth: true
});

});
</script>
<script>
$(document).ready(function() {
   $('#tablaLibros').DataTable({
    language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    },
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    responsive: true,
    autoWidth: true
});

});
</script>

<script>
function agregarUsuario() {
  Swal.fire({
    title: 'Agregar Nuevo Usuario',
    html: `
      <form id="formAgregarUsuario" class="text-start" action="controllers/agregarUsuario.php" method="POST">
        <div class="mb-3">
          <label for="nombre_usuario" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <div class="mb-3">
          <label for="apellido_usuario" class="form-label">Apellido</label>
          <input type="text" class="form-control" id="apellido_usuario" name="apellido_usuario" required>
        </div>
        <div class="mb-3">
          <label for="email_usuario" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" id="email_usuario" name="email_usuario"  autocomplete="username" required>
        </div>
        <div class="mb-3">
          <label for="password_usuario" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password_usuario" autocomplete="current-password" name="password_usuario" required>
        </div>
        <div class="mb-3">
          <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
          <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
            <option value="" selected disabled>Seleccione un tipo</option>
            <option value="Administrador">Administrador</option>
            <option value="Empleado">Empleado</option>
            <option value="Invitado">Invitado</option>
          </select>
        </div>
      </form>
    `,
    confirmButtonText: 'Agregar',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    focusConfirm: false,
    preConfirm: () => {
      const nombre = document.getElementById('nombre_usuario').value.trim();
      const apellido = document.getElementById('apellido_usuario').value.trim();
      const email = document.getElementById('email_usuario').value.trim();
      const password = document.getElementById('password_usuario').value.trim();
      const tipo = document.getElementById('tipo_usuario').value.trim();

      if (!nombre || !apellido || !email || !password || !tipo) {
        Swal.showValidationMessage('Por favor, complete todos los campos.');
        return false;
      }

      const formData = new FormData();
      formData.append('nombre_usuario', nombre);
      formData.append('apellido_usuario', apellido);
      formData.append('email_usuario', email);
      formData.append('password_usuario', password);
      formData.append('tipo_usuario', tipo);
      return formData;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = result.value;

      $.ajax({
        url: 'controllers/agregarUsuario.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire(' Éxito', response.message, 'success').then(() => {
              location.reload();
            });
          } else {
            Swal.fire(' Atención', response.message, 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.error("Error AJAX:", error, xhr.responseText);
          Swal.fire(' Error', 'El servidor no respondió correctamente.', 'error');
        }
      });
    }
  });
}
</script>

<script>
function abrirCrearReserva() {
  Swal.fire({
    title: 'Reserva',
    html: `
      <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar Libro..." onkeyup="buscarLibro(this.value)">
      <div id="sugerencias" style="text-align:left; max-height:150px; overflow-y:auto;"></div>
      <table class="table table-bordered" id="tablaLibros" style="margin-top:10px; font-size:14px;">
          <thead>
              <tr>
                  <th>Titulo</th>
                  <th>Autor</th>
                  <th>Cantidad</th>
                  <th>Estado</th>
                  <th>Acción</th>
              </tr>
          </thead>
          <tbody></tbody>
      </table>
    `,
    width: 800,
    showCancelButton: true,
    confirmButtonText: 'Confirmar Reserva',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
      window.tbodyModal = Swal.getPopup().querySelector("#tablaLibros tbody"); // para que busque la tabla libro dentro del modal y no del html index
    },
    preConfirm: () => {
      return new Promise((resolve, reject) => {
        const libros = [];

        Swal.getPopup().querySelectorAll('#tablaLibros tbody tr').forEach(row => { //busca dentro del sweet alert 
          const id = parseInt(row.getAttribute('data-id'));
          const cantidad = parseInt(row.querySelector('.cantidad').value);
          if (id && cantidad > 0) {
            libros.push({ id, cantidad });
          }
        });

        if (libros.length === 0) {
          reject('Agrega al menos un libro.');
          return;
        }

     $.ajax({
  url: './controllers/agregarReserva.php',
  type: 'POST',
  dataType: 'json', // 🔹 muy importante
  data: { libros: JSON.stringify(libros) },
  success: function (res) {
    if (res.success) resolve(res.message);
    else reject(res.message);
  },
  error: function (xhr, status, error) {
    console.error("Error AJAX:", xhr.responseText);
    reject('No se pudo agregar la reserva.');
  }
});

      }).catch(error => Swal.showValidationMessage(error));
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      Swal.fire('¡Éxito!', result.value, 'success').then(() => location.reload());
    }
  });
}

// Buscar libros mientras se escribe
function buscarLibro(texto) {
    // Si el texto es muy corto, limpia las sugerencias
    if (texto.length < 2) {
        document.getElementById('sugerencias').innerHTML = '';
        return;
    }

    $.ajax({
        url: './controllers/buscarLibro.php', 
        type: 'POST',
        dataType: 'json', 
        data: { query: texto },
        success: function (libros) {
            let html = '<ul class="list-group">';

            if (libros.length > 0) {
                libros.forEach(libro => {
                    let disponible;
            if (libro.cantidad_libro > 0) {
                disponible = true;
            } else {
                disponible = false;
            }

            // Si esta disponible
            if (disponible) {
                html += `
                    <li class="list-group-item list-group-item-action"
                        onclick="agregarLibro('${libro.id_libro}', '${libro.titulo_libro}', '${libro.autor_libro}', '${libro.cantidad_libro}')">
                        <strong>${libro.titulo_libro}</strong> <br>
                        <small>Autor: ${libro.autor_libro}</small><br>
                        <span class="text-success fw-semibold">Disponible: ${libro.cantidad_libro}</span>
                    </li>
                `;
            } 
            // Si NO esta disponible
            else {
                html += `
                    <li class="list-group-item disabled bg-light text-muted" style="cursor: not-allowed;">
                        <strong>${libro.titulo_libro}</strong> <br>
                        <small>Autor: ${libro.autor_libro}</small><br>
                        <span class="text-danger fw-semibold">No disponible</span>
                    </li>
                `;
            }
        });
            } else {
                html += `<li class="list-group-item text-muted">No se encontraron libros.</li>`;
            }

            html += '</ul>';
            document.getElementById('sugerencias').innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("❌ Error en la búsqueda:", error);
            document.getElementById('sugerencias').innerHTML = '<div class="text-danger ps-2">Error al buscar libros.</div>';
        }
    });
}

// Agregar libro a la tabla
function agregarLibro(id, titulo, autor, stock) {
  const tbody = Swal.getPopup().querySelector("#tablaLibros tbody"); 

  // Evitar duplicados
if ([...tbody.querySelectorAll("tr")].some(row => row.dataset.id === id)) {
  const alerta = document.createElement("div");
  alerta.className = "alert alert-warning alert-dismissible fade show mt-2";
  alerta.role = "alert";
  alerta.innerHTML = `
    <strong>Atención:</strong> Este libro ya fue agregado.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  const contenedor = document.querySelector("#sugerencias") || document.querySelector("#tablaLibrosModal");
  contenedor.prepend(alerta); //inserta la alerta al incio 

  setTimeout(() => alerta.remove(), 3000);
  return;
}
// Verificar disponibilidad
  let disponibilidad;
  if (stock > 0) {
    disponibilidad = "Disponible";
  } else {
    disponibilidad = "No disponible";
  }

  const fila = document.createElement('tr');
  fila.dataset.id = id;

  fila.innerHTML = `
    <td>${titulo}</td>
    <td>${autor}</td>
    <td>
      <input type="number" value="1" min="1" max="${stock}" 
             class="form-control form-control-sm cantidad">
      <small class="text-muted">Stock: ${stock}</small>
    </td>
    <td>${disponibilidad}</td>
    <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Quitar</button></td>
  `;

  tbody.appendChild(fila);

  document.getElementById('sugerencias').innerHTML = '';
  document.getElementById('busquedaProducto').value = '';
}

</script>

<!-- script que hace que el formulario de documentos abra en nueva pestaña o descargue segun la seleccion -->
<script>
document.querySelectorAll('.form-documentos').forEach(form => {
  form.addEventListener('submit', e => {
    const salida = form.querySelector('select[name="salida"]');
    if (salida && salida.value === 'I') {
      form.setAttribute('target', '_blank'); // abre en nueva pestaña
    } else {
      form.removeAttribute('target'); // descarga en la misma
    }
  });
});
</script>


<script>
function editarUsuario(id) {
    // Primero obtenemos los datos del usuario
    $.ajax({
        url: 'controllers/info_usuario.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (!response.success) {
                Swal.fire('⚠️ Atención', response.message, 'warning');
                return;
            }

            const usuario = response.data;

            //se crea variable para cargar el select con el que tiene el usuario
            let opcionesCargo = '';

            if (usuario.tipo_usuario === 'Administrador') {
                opcionesCargo = `
                    <option value="Administrador" selected>Administrador</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Cliente">Cliente</option>
                `;
            } else if (usuario.tipo_usuario === 'Empleado') {
                opcionesCargo = `
                    <option value="Administrador">Administrador</option>
                    <option value="Empleado" selected>Empleado</option>
                    <option value="Cliente">Cliente</option>
                `;
            } else if (usuario.tipo_usuario === 'Cliente') {
                opcionesCargo = `
                    <option value="Administrador">Administrador</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Cliente" selected>Cliente</option>
                `;
            }

            Swal.fire({
                title: 'Editar Usuario',
                html: `
                    <form id="formEditarUsuario" class="form-control" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" value="${usuario.nombre_usuario}" required>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" value="${usuario.apellido_usuario}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña Antigua</label>
                            <input type="password" class="form-control" id="passwordOld" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña Nueva</label>
                            <input type="password" class="form-control" id="passwordNueva" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"> Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" value="${usuario.email_usuario}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cargo</label>
                             <select class="form-control" id="cargo" required>
                                ${opcionesCargo} // se llama la variable 
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,

                preConfirm: () => {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('nombre', $('#nombre').val().trim());
                    formData.append('apellido', $('#apellido').val().trim());
                    formData.append('correo', $('#correo').val().trim());
                    formData.append('passwordOld', $('#passwordOld').val().trim());
                    formData.append('passwordNueva', $('#passwordNueva').val().trim());
                    formData.append('cargo', $('#cargo').val());
                    return formData;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'controllers/editar_Usuario.php',
                        type: 'POST',
                        data: result.value,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                Swal.fire('✅ Éxito', res.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('⚠️ Atención', res.message, 'warning');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('❌ Error', 'Error en el servidor', 'error');
                            console.error(error, xhr.responseText);
                        }
                    });
                }
            });
        },
        error: function() {
            Swal.fire('❌ Error', 'No se pudo cargar la información del usuario', 'error');
        }
    });
}

</script>

<script>
function eliminarEmpleado(id) {
  Swal.fire({
    title: "¿Deseas eliminar el empleado?",
    text: "No podrás revertir esto",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Eliminado!",
        text: "El empleado ha sido eliminado exitosamente.",
        icon: "success",
        timer: 2000,      // el tiempo que se demora en cerrar el alert 
        showConfirmButton: false
      }).then(() => {
        // Redirige al controlador de eliminar  cuando cierra el alert 
        window.location.href = "./controllers/eliminar.php?id=" + id;
      });
    }
  });
}
</script>

  </body>
  <!--end::Body-->
</html>
