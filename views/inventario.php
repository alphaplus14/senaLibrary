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
//consulta para obtener los libros
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
              
              
              <li class="nav-item">
                <a href="./Documentos.php" class="nav-link">
                  <i class="bi bi-file-earmark-pdf me-2"> </i>    
                  <span>
                   Documentos 
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./inventario.php" class="nav-link active">
                 <i class="nav-icon bi bi-box-seam me-2"> </i>
                  <span> Libros </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./reservas.php" class="nav-link">
                 <i class="bi bi-ticket-perforated me-2"> </i>
                  <span> Reservas </span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./historialPrestamosAdmin.php" class="nav-link">
                 <i class="bi bi-clock-history me-2"></i>
                  <span> Historial </span>
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
                <h3 class="mb-0">Inventario</h3>
              </div>    
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="./inventario.php">Inventario</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Lista de Libros</li>
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
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 d-flex gap-2">
                <?php if ($rol == 'Administrador'): ?>
                     <button type="button" class="btn btn-success" onclick="agregarLibro()">➕ Agregar Nuevo Libro </button>
                <?php endif; ?>
                </div>
            </div>
            <div class="row">
              <!--begin::Col-->
                <div class="table-responsive">
                        <table id="tablaLibros" class="table table-striped table-bordered" width="100%">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Categoria</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <?php if($rol == "Administrador"): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['id_libro']; ?></td>
                            <td><?php echo $fila['titulo_libro']; ?></td>
                            <td><?php echo $fila['autor_libro']; ?></td>
                            <td><?php echo $fila['ISBN_libro']; ?></td>
                            <td><?php echo $fila['categoria_libro']; ?></td>
                            <td><?php echo $fila['cantidad_libro']; ?></td>
                            <td>
                              <?php if($fila['cantidad_libro'] == 0): ?>
                                <span class="badge bg-danger">No disponible</span>
                              <?php else: ?>
                                <span class="badge bg-success"><?= $fila['disponibilidad_libro'] ?></span>
                              <?php endif; ?>
                            </td>
                            <?php if($rol == "Administrador"): ?>
                            <td class="justify-content-center d-flex gap-1">
                        
                        <a class="btn btn-warning btn-sm"  title="editar" onclick="editarLibro(<?php echo $fila['id_libro']; ?>)">
    <i class="bi bi-pencil-square"></i>
    </a>
    | 
    <a class="btn btn-danger btn-sm"  
    href="javascript:void(0);" 
    onclick="eliminarLibro(<?php echo $fila['id_libro']; ?>)" 
    title="Eliminar"> 
        <i class="bi bi-trash"></i>
    </a>

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
function agregarLibro() {
  Swal.fire({
    title: 'Agregar Nuevo Libro',
    html: `
      <form id="formAgregarLibro" class="text-start" action="controllers/agregarLibro.php" method="POST">
        <div class="mb-3">
          <label for="titulo_libro" class="form-label">Titulo</label>
          <input type="text" class="form-control" id="titulo_libro" name="titulo_libro" required>
        </div>
        <div class="mb-3">
          <label for="autor_libro" class="form-label">Autor</label>
          <input type="text" class="form-control" id="autor_libro" name="autor_libro" required>
        </div>
        <div class="mb-3">
          <label for="ISBN" class="form-label">ISBN</label>
          <input type="text" class="form-control" id="ISBN" name="ISBN" required>
        </div>
        <div class="mb-3">
          <label for="categoria_libro" class="form-label">Categoria</label>
          <select class="form-select" id="categoria_libro" name="categoria_libro" required>
            <option value="" selected disabled>Seleccione un tipo</option>
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
          </select>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
        </div>
      </form>
    `,
    confirmButtonText: 'Agregar',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    focusConfirm: false,
    preConfirm: () => {
      const titulo = document.getElementById('titulo_libro').value.trim();
      const autor = document.getElementById('autor_libro').value.trim();
      const ISBN = document.getElementById('ISBN').value.trim();
      const categoria = document.getElementById('categoria_libro').value.trim();
      const cantidad = document.getElementById('cantidad').value.trim();

      if (!titulo || !autor || !ISBN || !categoria || !cantidad) {
        Swal.showValidationMessage('Por favor, complete todos los campos.');
        return false;
      }

      const formData = new FormData();
      formData.append('titulo_libro', titulo);
      formData.append('autor_libro', autor);
      formData.append('ISBN_libro', ISBN);
      formData.append('categoria_libro', categoria);
      formData.append('cantidad_libro', cantidad);
      return formData;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = result.value;

      $.ajax({
        url: '../controllers/agregarLibro.php',
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

function editarLibro(id) {
    // Primero obtenemos los datos del usuario
    $.ajax({
        url: '../controllers/info_libro.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (!response.success) {
                Swal.fire('⚠️ Atención', response.message, 'warning');
                return;
            }

            const libro = response.data;

            //se crea variable para cargar el select con el que tiene el usuario
            let categoriaLibro = '';

            if (libro.categoria_libro === 'Ficcion') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro === 'No ficcion') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'De Referencia') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'Libros de Texto') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'Tecnicos o Especializados') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'Practicos') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'Poeticos') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            } else if (libro.categoria_libro  === 'Religiosos') {
                categoriaLibro = `
            <option value="Ficcion">Ficcion</option>
            <option value="No Ficcion">No Ficcion</option>
            <option value="De Referencia"> De Referencia</option>
            <option value="Libros de Texto"> Libros de Texto</option>
            <option value="Tecnicos o Especializados"> Tecnicos o Especializados</option>
            <option value="Practicos"> Practicos</option>
            <option value="Poeticos"> Poeticos</option>
            <option value="Religiosos"> Religiosos</option>
                `;
            }

            Swal.fire({
                title: 'Editar Libro',
                html: `
                    <form id="formEditarLibro" class="form-control" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="titulo" value="${libro.titulo_libro}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" value="${libro.autor_libro}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="ISBN" value="${libro.ISBN_libro}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"> Categoria</label>
                            <select class="form-control" id="categoria" required>
                            ${categoriaLibro} //se llama la variable
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" value="${libro.cantidad_libro}" required>
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
                    formData.append('titulo', $('#titulo').val().trim());
                    formData.append('autor', $('#autor').val().trim());
                    formData.append('ISBN', $('#ISBN').val().trim());
                    formData.append('categoria', $('#categoria').val().trim());
                    formData.append('cantidad', $('#cantidad').val());
                    return formData;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/editar_Libro.php',
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
function eliminarLibro(id) {
  Swal.fire({
    title: "¿Deseas eliminar el libro?",
    text: "No podrás revertir esto",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Eliminado!",
        text: "El libro ha sido eliminado exitosamente.",
        icon: "success",
        timer: 2000,      // el tiempo que se demora en cerrar el alert 
        showConfirmButton: false
      }).then(() => {
        // Redirige al controlador de eliminar  cuando cierra el alert 
        window.location.href = "../controllers/eliminarLibro.php?id=" + id;
      });
    }
  });
}
</script>

  </body>
  <!--end::Body-->
</html>
