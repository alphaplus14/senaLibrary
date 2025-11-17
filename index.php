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

//consulta para obtener los libros
$resultadolibros=$mysql->efectuarConsulta("SELECT * FROM libro");
$resultado=$mysql->efectuarConsulta("SELECT * FROM usuario");
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

    .btn-info {
  background: linear-gradient(135deg, #17a2b8, #5bc0de);
  border: none;
  transition: all 0.3s ease;
  color: white;
  font-weight: 500;
  letter-spacing: 0.3px;
}

.btn-info:hover {
  transform: translateY(-5px) scale(1.05);
  background: linear-gradient(135deg, #5bc0de, #17a2b8);
  box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
}

.btn-info:active {
  transform: scale(0.98);
  box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

    .card {
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  cursor: pointer;
}

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
  min-width: 350px;          /* ancho mínimo para pantallas pequeñas */
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
  background-color: #048db7dd;
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
  min-height: 500px; 
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
    background-color: #00a390ff;
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
    background-color: #13882cff;
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
    <span class="d-none d-md-inline"><i class="bi bi-person-circle me-1"></i><?php echo $nombre; ?></span>
  </a>

              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
                <!-- Cabecera del usuario -->
                <li class="bg-primary text-white text-center rounded-top py-3">
                  <p class="mb-0 fw-bold fs-5"><?php echo  $nombre; ?></p>
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
            <span class="title"><img src="media/senalibrary icon.png"  style="width:30px; height:40px; vertical-align:middle; margin-right:5px; margin-top: 5px; margin-bottom: 5px;"> SenaLibrary</span>
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
              </li>
               <?php if ($rol == 'Administrador'): ?>
              <li class="nav-item">
                <a href="./views/usuarios.php" class="nav-link">
                  <i class="bi bi-file-earmark-person me-2"></i>
                  <span>Usuarios</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/inventario.php" class="nav-link">
                 <i class="bi bi-book me-2"> </i>
                  <span> Libros </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/reservas.php" class="nav-link">
                 <i class="bi bi-journal-richtext me-2"> </i>
                  <span> Reservas </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/historialPrestamosAdmin.php" class="nav-link">
                 <i class="bi bi-journal-arrow-down me-2"></i>
                  <span> Prestamos </span>
                </a>
              </li>
              <?php endif; ?>
               <?php if ($rol == 'Cliente'): ?>
              <li class="nav-item">
                <a href="./views/gestionarReserva.php" class="nav-link">
                 <i class="bi bi-calendar-check me-2 me-2"> </i>
                  <span> Gestionar Reserva </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/historialPrestamos.php" class="nav-link">
                  <i class="bi bi-clock-history me-2"></i>
                  <span> Historial </span>
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
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
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
        <!-- graficos de la pagina principal -->
        <?php if($rol == "Administrador"): ?>
          <div class="row">
            <div class="col-12 text-center mb-4">
              <h3 class="d-flex justify-content-center align-items-center"><img src="media/graficos icon.png" alt="PDF" style="width:40px; height:40px; vertical-align:middle; margin-right:10px;">Graficos:</h3>
            </div>
          </div>
        <div class="container mt-4">
          <div class="row">
            <!-- Gráfica grande a la izquierda -->
            <div class="col-lg-8">
              <div class="card" style="min-height: 660px; padding:1rem; border-radius:12px;">
                <div class="card-body">
                  <h4 class="titulo-seccion">
                    <i class="fa-solid fa-book"></i> Total de libros registrados
                  </h4>
                  <canvas id="graficoTotalLibros" width="400" height="410"></canvas>
                </div>
              </div>
            </div>

            <!-- Contenedor de las dos pequeñas a la derecha -->
            <div class="col-lg-4 d-flex flex-column justify-content-between">
              <div class="card mb-3" style="min-height: 320px; padding:1rem; border-radius:12px;">
                <div class="card-body">
                  <h4 class="titulo-seccion">
                    <i class="fa-solid fa-calendar-check"></i> Total de reservas realizadas
                  </h4>
                  <canvas id="graficoTotalReservas" width="300" height="100"></canvas>
                </div>
              </div>

              <div class="card" style="min-height: 320px; padding:1rem; border-radius:12px;">
                <div class="card-body">
                  <h4 class="titulo-seccion">
                    <i class="fa-solid fa-book-open-reader"></i> Total de préstamos realizados
                  </h4>
                  <canvas id="graficoTotalPrestamos" width="300" height="100"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
<!-- botones de los modulos cesar!!! -->
        <div class="container text-center mt-4">
          <div class="row g-3">
            <div class="col">
              <a href="./views/usuarios.php"> <button class="btn btn-info w-100">
                <i class="fa-solid fa-user"></i><img src="media/usuarios modulo icon.png" alt="PDF" style="width:30px; height:30px; vertical-align:middle; margin-right:6px;"> Usuarios
              </button> 
              </a>
            </div>
            <div class="col">
              <a href="./views/inventario.php"> <button class="btn btn-info w-100">
                <i class="fa-solid fa-book"></i><img src="media/libro icon.png" alt="PDF" style="width:30px; height:30px; vertical-align:middle; margin-right:6px;"> Libros
              </button>
              </a>
            </div>
            <div class="col">
              <a href="./views/reservas.php"> <button class="btn btn-info w-100">
                <i class="fa-solid fa-calendar-check"></i><img src="media/reservas icon.png" alt="PDF" style="width:30px; height:30px; vertical-align:middle; margin-right:6px;"> Reservas
              </button>
              </a>
            </div>
            <div class="col">
              <a href="./views/historialPrestamosAdmin.php"> <button class="btn btn-info w-100 ">
                <i class="fa-solid fa-book-open-reader"></i><img src="media/prestamos icon.png" alt="PDF" style="width:30px; height:30px; vertical-align:middle; margin-right:6px;"> Préstamos
              </button>
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php
        $hoy = date('Y-m-d');
        $inicioMes = date('Y-m-01');
        ?>
        <?php if ($rol == 'Administrador'): ?>
          <div class="row">
          <div class="col-12 text-center mt-4">
            <h3 class="mb-0"><img src="media/documentos icon.png" alt="PDF" style="width:40px; height:40px; vertical-align:middle; margin-right:6px;">Generar Documentos:</h3>
          </div>
        </div>
        <!-- === FORMULARIOS DE DOCUMENTOS === -->
        <div class="container-documentos">

          <!-- === PDF DE RESERVAS === -->
          <div class="card-documento">
        <h4 class="titulo-seccion">
          <i class="fa-solid fa-calendar-check"></i> Generar reporte de las reservas
        </h4>
            <form action="views/generar_pdf_reservas.php" target="_blank" method="get" id="formReservas" onsubmit="return validarRangoFechas(this);" class="form-documentos">
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
            <i class="fa-solid fa-file-pdf"> <img src="media/pdf icon.png" alt="PDF" style="width:20px; height:20px; vertical-align:middle; margin-right:6px;"></i> GENERAR PDF
          </button>
        </div>
                </div>
                
              </div>
            </form>
          </div>

          <!-- === PDF DE INVENTARIO === -->
        <div class="card-documento">
        <h4 class="titulo-seccion">
          <i class="fa-solid fa-boxes-stacked"></i> Generar reporte del inventario
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
                    <i class="fa-solid fa-file-pdf"><img src="media/pdf icon.png" alt="PDF" style="width:20px; height:20px; vertical-align:middle; margin-right:6px;"></i> GENERAR PDF  
                  </button>
                        <a href="views/generar_excel_inventario.php" class="btn-excel">
                            <i class="fa-solid fa-file-excel"><img src="media/excel icon.png" alt="PDF" style="width:20px; height:20px; vertical-align:middle; margin-right:6px;"></i>  EXCEL
                        </a>
                    </div>
                </div>
            </form>
        </div>

          <!-- === PDF DE PRÉSTAMOS === -->
          <div class="card-documento">
        <h4 class="titulo-seccion">
          <i class="fa-solid fa-handshake"></i> Generar reporte de préstamos
        </h4>
            <form action="views/generar_pdf_prestamos.php" target="_blank" method="get" class="form-documentos" id="formPrestamos" onsubmit="return validarRangoFechas(this);">
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
                    <i class="fa-solid fa-file-pdf"><img src="media/pdf icon.png" alt="PDF" style="width:20px; height:20px; vertical-align:middle; margin-right:6px;"></i> GENERAR PDF  
                  </button>
        <button type="submit" formaction="views/generar_excel_prestamos.php" class="btn-excel">
          <i class="fa-solid fa-file-excel"><img src="media/excel icon.png" alt="PDF" style="width:20px; height:20px; vertical-align:middle; margin-right:6px;"></i> EXCEL
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
    <?php if($rol == "Administrador"): ?>
        <script src="js/graficos_libro.js"></script>
        <script src="js/grafico_reservas.js"></script> 
        <script src="js/grafico_prestamos.js"></script>
    <?php endif; ?>
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
function abrirCrearReserva() {
  Swal.fire({
    title: 'Reserva',
    html: `
      <input type="text" id="busquedaProducto" class="swal2-input" placeholder="Buscar Libro..." onkeyup="buscarLibro(this.value)">
      <div id="sugerencias" style="text-align:left; max-height:150px; overflow-y:auto;"></div>

      <table class="table table-bordered" id="tablaLibros" style="margin-top:10px; font-size:14px;">
        <thead class="table-dark">
          <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <div id="fechaRecogidaContainer" style="display:none; margin-top:10px;">
        <label class="form-label">Fecha de Recogida</label>
        <input type="date" id="fechaRecogida" class="swal2-input" style="width:50%;">
      </div>
    `,
    width: 800,
    showCancelButton: true,
    confirmButtonText: 'Confirmar Reserva',
    cancelButtonText: 'Cancelar',

    didOpen: () => {
      window.tbodyModal = Swal.getPopup().querySelector("#tablaLibros tbody");

      const observer = new MutationObserver(() => {
        const filas = tbodyModal.querySelectorAll('tr').length;
        const contenedorFecha = Swal.getPopup().querySelector("#fechaRecogidaContainer");
        contenedorFecha.style.display = filas > 0 ? 'block' : 'none';
      });

      observer.observe(tbodyModal, { childList: true });
    },

    preConfirm: () => {
      return new Promise((resolve, reject) => {
        const libros = [];
        const popup = Swal.getPopup();

        popup.querySelectorAll('#tablaLibros tbody tr').forEach(row => {
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

        const fechaRecogida = popup.querySelector('#fechaRecogida').value;
        if (!fechaRecogida) {
          reject('Selecciona la fecha de recogida.');
          return;
        }

        $.ajax({
          url: './controllers/agregarReserva.php',
          type: 'POST',
          dataType: 'json',
          data: { 
            libros: JSON.stringify(libros),
            fechaRecogida: fechaRecogida
          },
          success: function (res) {
            if (res.success) resolve(res.message);
            else reject(res.message);
          },
          error: function (xhr) {
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
      <input type="text" value="1" 
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
/**
 * Valida que la fecha de inicio no sea posterior a la fecha de fin 
 * dentro de un formulario específico.
 * @param {HTMLFormElement} formElement - El elemento del formulario que se está enviando.
 * @returns {boolean} - true si la validación es exitosa, false si falla.
 */
function validarRangoFechas(formElement) {
    // 1. Encontrar los campos de fecha DENTRO de este formulario
    // Usamos querySelector('[name="nombreDelCampo"]') dentro del formulario
    const fechaInicioInput = formElement.querySelector('input[name="fechaInicio"]');
    const fechaFinInput = formElement.querySelector('input[name="fechaFin"]');

    // Comprobar que los campos existen (seguridad)
    if (!fechaInicioInput || !fechaFinInput) {
        console.error("No se encontraron los campos 'fechaInicio' o 'fechaFin' en el formulario.");
        return true; // Permitir el envío por defecto si algo falla en el script
    }

    // 2. Obtener los valores de las fechas
    const fechaInicio = new Date(fechaInicioInput.value);
    const fechaFin = new Date(fechaFinInput.value);

    // 3. Comparar las fechas
    // Si la Fecha de Inicio es mayor (más avanzada/futura) que la Fecha de Fin.
    if (fechaInicio > fechaFin) {
        Swal.fire({
            icon: 'error',
            title: 'Rango de fechas inválido',
            text: 'La Fecha de Inicio no puede ser posterior a la Fecha de Fin. Por favor, corrígelo.',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#ff0000ff'
        });
        
        // Enfoca el campo de inicio para guiar al usuario
        fechaInicioInput.focus(); 
        
        // Detiene el envío del formulario
        return false; 
    }

    // 4. Si la validación es exitosa, permite el envío del formulario
    return true;
}
</script>

  </body>
  <!--end::Body-->
</html>
