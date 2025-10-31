<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//conexion a la base de datos
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['tipo_usuario'])) {
    header("location: ./views/login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();

$rol= $_SESSION['tipo_usuario'];
$nombre=$_SESSION['nombre_usuario'];
$idUsuario=$_SESSION['id_usuario'];


$mysql = new MySQL();
$mysql->conectar();

$resultado=$mysql->efectuarConsulta("SELECT prestamo.*,reserva.estado_reserva FROM prestamo inner join reserva on reserva.id_reserva=prestamo.fk_reserva where reserva.fk_usuario=$idUsuario");
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
    <link rel="preload" href="../css/adminlte.css" as="style" />
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
                  <i class="bi bi-speedometer me-2"></i>
                  <span>
                    Dashboard
                  </span>
                  </a>
                </li>

               <?php if ($rol == 'Cliente'): ?>
              <li class="nav-item">
                <a href="./gestionarReserva.php" class="nav-link">
                 <i class="bi bi-calendar-check me-2"> </i>
                  <span> Gestionar Reserva </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./historialPrestamos.php" class="nav-link active">
                  <i class="nav-icon bi bi-clock-history me-2"></i>
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
          <div class="container-fluid">
            <!--begin::Row-->
            <!-- vista de diferentes usuarios  -->
            <div class="row">
                 <div class="col-sm-6">
                <h3 class="mb-0">Historial de Prestamos</h3>
              </div>    
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="table-responsive mb-5">
                        <table id="tablaPrestamos" class="table table-striped table-bordered" width="100%">
                            <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Fecha Prestamo </th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                <td><?= $fila['id_prestamo'] ?></td>
                                <td><?= $fila['fecha_prestamo'] ?></td>
                                <td>
                                  <?php
                                    $estado = $fila['estado_reserva'];
                                    $badgeClass = '';
                                    $icono = '';
                                    $texto = '';

                                    if ($estado == 'Aprobada') {
                                        $badgeClass = 'bg-success';
                                        $icono = 'bi-check-circle-fill';
                                        $texto = 'Aprobada';
                                    } elseif ($estado == 'Rechazada') {
                                        $badgeClass = 'bg-danger';
                                        $icono = 'bi-x-circle-fill';
                                        $texto = 'Rechazada';
                                    } elseif ($estado == 'Cancelada') {
                                        $badgeClass = 'bg-secondary';
                                        $icono = 'bi-slash-circle-fill';
                                        $texto = 'Cancelada';
                                    } else {
                                        // Si llega otro valor inesperado, se muestra neutro
                                        $badgeClass = 'bg-light text-dark border';
                                        $icono = 'bi-question-circle';
                                        $texto = htmlspecialchars($estado);
                                    }
                                  ?>
                                  <span class="badgige <?php echo $badgeClass; ?> px-2 py-2 fw-semibold">
                                    <i class="bi <?php echo $icono; ?> me-1"></i>
                                    <?php echo $texto; ?>
                                  </span>
                                </td>
                                <td class="text-center">
                                     <button class="btn btn-info btn-sm" onclick="verDetalle(<?= $fila['fk_reserva'] ?>)"><i class="bi bi-eye"></i></button> <small> Ver detalle </small>
                                </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

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
   $('#tablaPrestamos').DataTable({
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
function verDetalle(idReserva) {
    $.ajax({
        url: '../controllers/detalleReserva.php',
        type: 'POST',
        data: { id_reserva: idReserva },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                let tabla = `
                    <table class="table table-striped align-middle" style="width:100%; text-align:left;">
                        <thead class="table-dark">
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Categoría</th>
                                <th>Fecha Reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                res.detalle.forEach(item => {
                    tabla += `
                        <tr>
                            <td>${item.ISBN_libro}</td>
                            <td>${item.titulo_libro}</td>
                            <td>${item.autor_libro}</td>
                            <td>${item.categoria_libro}</td>
                            <td>${item.fecha_reserva}</td>
                        </tr>
                    `;
                });

                tabla += `
                        </tbody>
                    </table>
                `;
                    // Mostrar alerta con detalle
                Swal.fire({
                    title: `<i class="bi bi-book"></i> Detalle de la Reserva #${idReserva}`,
                    html: tabla,
                    icon: 'info',
                    confirmButtonText: '<i class="bi bi-check-circle"></i> Cerrar',
                    confirmButtonColor: '#3085d6',
                    width: 900
                });
                
            } else {
                Swal.fire('Sin resultados', res.message || 'No se encontraron libros en esta reserva.', 'warning');
            }
        },
        error: function (xhr) {
            console.error('Respuesta del servidor:', xhr.responseText);
            Swal.fire('Error', 'No se pudo obtener la información de la reserva.', 'error');
        }
    });
}
</script>
  </body>
  <!--end::Body-->
</html>
