<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//conexion a la base de datos
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    header("location: ./login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();

$rol= $_SESSION['tipo_usuario'];
$nombre=$_SESSION['nombre_usuario'];



$mysql = new MySQL();
$mysql->conectar();
//consulta para obtener los usuarios
$resultado=$mysql->efectuarConsulta("SELECT * FROM libro");


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
    <link rel="stylesheet" href="../css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- Estilo propio -->
     <link rel="stylesheet" href="../css/style.css">

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


  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <!-- toco meterle style para que el formulario combinara con el resto de la pagina -->
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


</style>
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
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="../index.php" class="nav-link">Inicio</a>
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
      <a href="./perfilUsuario.php" class="dropdown-item d-flex align-items-center py-2">
        <i class="bi bi-person me-2 text-secondary"></i> Perfil
      </a>
    </li>

    <!-- Separador -->
    <li><hr class="dropdown-divider m-0"></li>

    <!-- Opción de cerrar sesión -->
    <li>
      <a href="../controllers/logout.php" class="dropdown-item d-flex align-items-center text-danger py-2">
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
          <a href="../index.php" class="brand-link">
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
                <a href="../index.php" class="nav-link">
                  <i class="nav-icon bi bi-speedometer me-2"></i>
                  <span>
                    Dashboard
                    
                  </span>
                  </a>
              
              
              <li class="nav-item">
                <a href="./documentos.php" class="nav-link active">
                  <i class="bi bi-file-earmark-pdf me-2"> </i>    
                  <span>
                   Documentos 
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./inventario.php" class="nav-link">
                 <i class="bi bi-box-seam me-2"> </i>
                  <span> Inventario </span>
                </a>
              </li>

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
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Documentos</h3>
              </div>    
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item active"><a href="./documentos.php">Documentos</a></li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
<?php
$hoy = date('Y-m-d');
$inicioMes = date('Y-m-01');
?>
<!-- === FORMULARIOS DE DOCUMENTOS === -->
<div class="container-documentos">

  <!-- === PDF DE RESERVAS === -->
  <div class="card-documento">
    <h4 class="titulo-seccion">
      <i class="fa-solid fa-calendar-check"></i> PDF DE LAS RESERVAS:
    </h4>
    <form action="generar_pdf_reservas.php" method="get" class="form-documentos">
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
          <button type="submit" class="btn-generar">
            <i class="fa-solid fa-file-pdf"></i>  GENERAR PDF
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- === PDF DE INVENTARIO === -->
  <div class="card-documento">
    <h4 class="titulo-seccion">
      <i class="fa-solid fa-boxes-stacked"></i> PDF DEL INVENTARIO ACTUAL:
    </h4>
    <form action="generar_pdf_inventario.php" method="get" class="form-documentos">
      <div class="row-form">
        <div class="form-group">
          <label for="salida">Ver:</label>
          <select id="salida" name="salida">
            <option value="I">Ver en el navegador</option>
            <option value="D">Descargar</option>
          </select>
        </div>

        <div class="form-group">
          <button type="submit" class="btn-generar">
            <i class="fa-solid fa-box-open"></i> GENERAR PDF
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- === PDF DE PRÉSTAMOS === -->
  <div class="card-documento">
    <h4 class="titulo-seccion">
      <i class="fa-solid fa-handshake"></i> PDF DE LOS PRÉSTAMOS:
    </h4>
    <form action="generar_pdf_prestamos.php" method="get" class="form-documentos">
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
          <button type="submit" class="btn-generar">
            <i class="fa-solid fa-file-pdf"></i> GENERAR PDF
          </button>
        </div>
      </div>
    </form>
  </div>

</div>





          <!--end::Container-->
        </div>
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
    <script src="../public/js/adminlte.js"></script>
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







  </body>
  <!--end::Body-->
</html>
