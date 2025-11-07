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
//consulta para obtener los libros
$resultado=$mysql->efectuarConsulta(" select reserva.*,CONCAT(usuario.nombre_usuario, ' ', usuario.apellido_usuario) as nombre_usuario from reserva inner join usuario on reserva.fk_usuario=usuario.id_usuario where estado_reserva='Pendiente'");
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
              <li class="nav-item">
                <a href="./usuarios.php" class="nav-link">
                  <i class="bi bi-file-earmark-person me-2"></i>
                  <span>Usuarios</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="inventario.php" class="nav-link">
                 <i class="bi bi-book me-2"></i>
                  <span>Libros</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="reservas.php" class="nav-link active">
                 <i class="nav-icon bi bi-journal-richtext me-2"></i>
                  <span>Reservas</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="historialPrestamosAdmin.php" class="nav-link">
                 <i class="bi bi-journal-arrow-down me-2"></i>
                  <span>Prestamos</span>
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
                <h3 class="mb-0">Reservas</h3>
              </div>    
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="./inventario.php">Reservas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Gestion de Reservas</li>
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
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
                <div class="table-responsive">
                        <table id="tablaReservas" class="table table-striped table-bordered" width="100%">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Cliente</th>
                            <th>Fecha Reserva</th>
                            <th>Fecha Recogida</th>
                            <th>Estado</th>
                            <?php if($rol == "Administrador"): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['id_reserva']; ?></td>
                            <td><?php echo $fila['nombre_usuario']; ?></td>
                            <td><?php echo $fila['fecha_reserva']; ?></td>
                            <td><?php echo $fila['fecha_recogida']; ?></td>
                            <td>
                                <span class="badge bg-warning text-dark"><?php echo $fila['estado_reserva']; ?></span>
                            </td>
                            <?php if($rol == "Administrador"): ?>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" onclick="verDetalle(<?= $fila['id_reserva'] ?>)"><i class="bi bi-eye"></i></button> <small> Ver detalle </small>
                                     
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                        </table>
                </div>
                
              <!-- /.Start col -->
            </div>
            <!-- /.row (main row) -->
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
   $('#tablaReservas').DataTable({
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
                    <table class="table table-striped" style="width:100%; text-align:left;">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
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
                            <td>${item.nombre_usuario}</td>
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

                    <div class="mt-3">
                      <label class="form-label fw-bold">Días de Préstamo</label>
                      <select id="dias_prestamo" class="form-control">
                          <option value="" selected disabled>Seleccione una cantidad de días</option>
                          <option value="5">5 Días</option>
                          <option value="10">10 Días</option>
                          <option value="15">15 Días</option>
                      </select>
                      <small class="text-muted">*Requerido para aprobar la reserva</small>
                    </div>
                `;

                // Obtener el estado de la reserva del elemento que trae del json
                let estadoReserva = res.detalle[0].estado_reserva;

                // Si la reserva está Pendiente, mostrar botones
                if (estadoReserva === 'Pendiente') {
                    Swal.fire({
                        title: '<i class="bi bi-book"></i> Detalle de la Reserva #' + idReserva,
                        html: tabla,
                        icon: 'info',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-check-circle"></i> Aprobar Reserva',
                        denyButtonText: '<i class="bi bi-x-circle"></i> Rechazar Reserva',
                        cancelButtonText: '<i class="bi bi-arrow-left"></i> Cerrar',
                        confirmButtonColor: '#28a745',
                        denyButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        width: 900,
                        // Validar antes de cerrar el modal
                        preConfirm: () => {
                            const diasPrestamo = document.getElementById('dias_prestamo').value;
                            if (!diasPrestamo) {
                                Swal.showValidationMessage('Debe seleccionar los días de préstamo');
                                return false;
                            }
                            return { diasPrestamo: diasPrestamo };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Pasar los días de préstamo a la función
                            aprobarReserva(idReserva, result.value.diasPrestamo);
                        } else if (result.isDenied) {
                            rechazarReserva(idReserva);
                        }
                    });
                } else {
                    // Si no esta pendiente, solo mostrar información
                    Swal.fire({
                        title: '<i class="bi bi-book"></i> Detalle de la Reserva #' + idReserva,
                        html: tabla.replace('<div class="mt-3">', `<div class="alert alert-info"><strong>Estado:</strong> ${estadoReserva}</div><div class="mt-3" hidden>`),
                        icon: 'info',
                        confirmButtonText: '<i class="bi bi-check"></i> Cerrar',
                        confirmButtonColor: '#007bff',
                        width: 900
                    });
                }
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function (xhr) {
            console.log('Respuesta del servidor:', xhr.responseText);
            Swal.fire('Error', 'No se pudo obtener la información.', 'error');
        }
    });
}


function aprobarReserva(idReserva, diasPrestamo) {
    Swal.fire({
        title: '¿Aprobar reserva?',
        text: `Se aprobará la reserva por ${diasPrestamo} días`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check-circle"></i> Sí, aprobar',
        cancelButtonText: '<i class="bi bi-x"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controllers/aprobarReserva.php',
                type: 'POST',
                data: { 
                    id_reserva: idReserva,
                    dias_prestamo: diasPrestamo  // Enviamos los dias
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: '¡Aprobada!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: '<i class="bi bi-check"></i> OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                    Swal.fire('Error', 'No se pudo aprobar la reserva.', 'error');
                }
            });
        }
    });
}

function rechazarReserva(idReserva) {
    Swal.fire({
        title: '¿Rechazar reserva?',
        text: "Esta acción rechazará la reserva",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-x-circle"></i> Sí, rechazar',
        cancelButtonText: '<i class="bi bi-arrow-left"></i> Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controllers/rechazarReserva.php',
                type: 'POST',
                data: { id_reserva: idReserva },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: '¡Rechazada!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: '<i class="bi bi-check"></i> OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                    Swal.fire('Error', 'No se pudo rechazar la reserva.', 'error');
                }
            });
        }
    });
}
</script>

  </body>
  <!--end::Body-->
</html>
