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
$resultado = $mysql->efectuarConsulta("
    SELECT 
        libro.*,
        GROUP_CONCAT(categorias.nombre_categoria SEPARATOR ', ') as categorias
    FROM libro
    LEFT JOIN categorias_has_libro ON libro.id_libro = categorias_has_libro.libro_id_libro
    LEFT JOIN categorias ON categorias_has_libro.categorias_id_categoria = categorias.id_categoria
    GROUP BY libro.id_libro
    ORDER BY libro.id_libro DESC
");
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
            <span class="title"><img src="../media/senalibrary icon.png"  style="width:30px; height:40px; vertical-align:middle; margin-right:5px; margin-top: 5px; margin-bottom: 5px;"> SenaLibrary</span>
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
                <a href="inventario.php" class="nav-link active">
                 <i class="nav-icon bi bi-book me-2"></i>
                  <span>Libros</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="reservas.php" class="nav-link">
                 <i class="bi bi-journal-richtext me-2"></i>
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
            <div class="position-relative">
              <h3 class="text-center">
                <i class="bi bi-bookshelf"></i> Libros
              </h3>
              <ol class="breadcrumb position-absolute end-0 top-50 translate-middle-y">
                <li class="breadcrumb-item"><a href="./inventario.php">Libros</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista de libros</li>
              </ol>
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
                     <button type="button" class="btn btn-success" onclick="agregarLibro()">➕ Libro </button>
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
                                  <td>
                                      <?php 
                                      // Mostrar categorias como badges
                                      if (!empty($fila['categorias'])) {
                                          $categorias = explode(', ', $fila['categorias']);
                                          foreach($categorias as $categoria): 
                                      ?>
                                          <span class="badge bg-info me-1 mb-1"><?php echo htmlspecialchars($categoria); ?></span>
                                      <?php 
                                          endforeach;
                                      } else {
                                          echo '<span class="badge bg-secondary">Sin categoría</span>';
                                      }
                                      ?>
                                  </td>
                                  <td><?php echo $fila['cantidad_libro']; ?></td>
                                  <td>
                                    <?php if ($fila['disponibilidad_libro'] === 'Inactivo'): ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?= $fila['disponibilidad_libro'] ?></span>
                                    <?php endif; ?>
                                  </td>
                                  <?php if($rol == "Administrador"): ?>
                                  <td class="justify-content-center d-flex gap-1">
                                    <a class="btn btn-warning btn-sm" title="editar" onclick="editarLibro(<?php echo $fila['id_libro']; ?>)">
                                      <i class="bi bi-pencil-square"></i>
                                    </a>
                                    | 
                                    <?php if($fila['disponibilidad_libro']=='Disponible'):?>
                                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" 
                                      onclick="eliminarLibro(<?php echo $fila['id_libro']; ?>)" title="Eliminar"> 
                                      <i class="bi bi-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <a class="btn btn-success btn-sm" href="javascript:void(0);" 
                                      onclick="activarLibro(<?php echo $fila['id_libro']; ?>)" title="Activar"> 
                                      <i class="bi bi-check"></i>
                                    </a>
                                    <?php endif; ?>
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
          <label for="categoria" class="form-label">Categoria</label>
          <input type="text" id="busquedaCategoria" class="form-control" placeholder="Buscar Categoria..." onkeyup="buscarCategoria(this.value)">
          <input type="hidden" id="categoria_libro" name="categoria_libro">
          <div id="categoriasSeleccionadas"></div>
          <div id="sugerencias" style="text-align:left; max-height:200px; margin-top: 5px;"></div>
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
      const categorias = document.getElementById('categoria_libro').value.trim();
      const cantidad = document.getElementById('cantidad').value.trim();

      if (!titulo || !autor || !ISBN || !categorias || !cantidad) {
        Swal.showValidationMessage('Por favor, complete todos los campos.');
        return false;
      }

      const formData = new FormData();
      formData.append('titulo_libro', titulo);
      formData.append('autor_libro', autor);
      formData.append('ISBN_libro', ISBN);
      formData.append('categoria_libro', categorias);
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

function buscarCategoria(texto) {
    // Si el texto es muy corto, limpia las sugerencias
    if (texto.length < 2) {
        document.getElementById('sugerencias').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscarCategoria.php', 
        type: 'POST',
        dataType: 'json', 
        data: { query: texto },
        success: function (categorias) {
            let html = '<ul class="list-group">';
            // se utiliza .replace para que no rompa el codigo con comillas
            if (categorias.length > 0) {
                categorias.forEach(categoria => {
                    html += `
                        <li class="list-group-item list-group-item-action" 
                            style="cursor: pointer;" 
                            onclick="seleccionarCategoria(${categoria.id}, '${categoria.nombre_categoria.replace(/'/g, "\\'")}')">
                            ${categoria.nombre_categoria}
                        </li>
                    `;
                });
                html += '</ul>';
            } else {
                html += `
                    <div class="alert alert-info mb-0">
                        <small>No se encontró la categoría "${texto}"</small>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" onclick="agregarNuevaCategoria('${texto.replace(/'/g, "\\'")}')">
                        <i class="bi bi-plus-circle"></i> Agregar nueva categoría
                    </button>
                `;
            }

            document.getElementById('sugerencias').innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("❌ Error en la búsqueda:", error);
            document.getElementById('sugerencias').innerHTML = '<div class="text-danger ps-2">Error al buscar categorias.</div>';
        }
    });
}

let categoriasSeleccionadas = []; // lista de id

function seleccionarCategoria(id, nombre) {    
    // Convertir a numero
    id = parseInt(id);
    console.log('ID convertido:', id, 'Tipo:', typeof id);
    
    // Evitar repetidos
    if (categoriasSeleccionadas.includes(id)) {
        // Limpiar busqueda
        document.getElementById('sugerencias').innerHTML = '';
        document.getElementById('busquedaCategoria').value = '';
        return;
    }

    categoriasSeleccionadas.push(id);

    // Actualizar input oculto (lo enviamos como JSON)
    document.getElementById('categoria_libro').value = JSON.stringify(categoriasSeleccionadas);

    // Agregar chip visual
    const contenedor = document.getElementById('categoriasSeleccionadas');
    const chip = document.createElement('span');

    chip.style.cssText = `
        display: inline-flex;
        align-items: center;
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 14px;
        border: 1px solid #c8e6c9;
    `;
    chip.innerHTML = `
        ${nombre}
        <span 
            onclick="eliminarCategoria(${id}, this)" 
            style="
                margin-left: 8px;
                font-size: 16px;
                cursor: pointer;
            "
        >&times;</span>
    `;

    contenedor.appendChild(chip);

    // Limpiar sugerencias
    document.getElementById('sugerencias').innerHTML = '';
    document.getElementById('busquedaCategoria').value = '';
}

function eliminarCategoria(id, elemento) {
    // Convertir a numero
    id = parseInt(id);
    
    // Remover del arreglo
    categoriasSeleccionadas = categoriasSeleccionadas.filter(catId => catId !== id);

    // Actualizamos el input oculto
    document.getElementById('categoria_libro').value = JSON.stringify(categoriasSeleccionadas);

    // Eliminar visualmente la viñeta
    elemento.parentNode.remove();
}

function agregarNuevaCategoria(nombreCategoria) {
    Swal.fire({
        title: 'Agregar Nueva Categoría',
        html: `
            <input type="text" id="nuevaCategoria" class="form-control" value="${nombreCategoria}" placeholder="Nombre de la categoría">
        `,
        confirmButtonText: 'Guardar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const nombre = document.getElementById('nuevaCategoria').value.trim();
            if (!nombre) {
                Swal.showValidationMessage('Por favor, ingrese el nombre de la categoría.');
                return false;
            }
            return { nombre: nombre };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controllers/agregarCategoria.php',
                type: 'POST',
                dataType: 'json',
                data: { nombre_categoria: result.value.nombre },
                success: function(response) {
                    if (response.success) {

                        //agregar automaticamente
                        seleccionarCategoria(response.id, result.value.nombre);

                        Swal.fire({
                            icon: 'success',
                            title: 'Categoría agregada correctamente',
                            timer: 1000,
                            showConfirmButton: false
                        });

                        document.getElementById('busquedaCategoria').value = '';
                        document.getElementById('sugerencias').innerHTML = '';

                    } else {
                        Swal.fire('Error', response.message || 'No se pudo agregar la categoría', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al agregar categoría:", error);
                    Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                }
            });
        }
    });
}

</script>

<script>
function editarLibro(id) {
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

            // Convertir categorias_ids en arreglo real
            window.categoriasSeleccionadas = Array.isArray(libro.categorias_ids)
                ? libro.categorias_ids
                : libro.categorias_ids
                    ? libro.categorias_ids.split(',').map(id => parseInt(id.trim()))
                    : [];

            Swal.fire({
                title: 'Editar Libro',
                html: `
                    <form id="formEditarLibro" class="text-start" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo_edit"
                                   value="${libro.titulo_libro.replace(/"/g, '&quot;')}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor_edit"
                                   value="${libro.autor_libro.replace(/"/g, '&quot;')}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="ISBN_edit"
                                   value="${libro.ISBN_libro}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categorías</label>
                            <input type="text" id="busquedaCategoria_edit" class="form-control"
                                   placeholder="Buscar Categoría..." onkeyup="buscarCategoriaEdit(this.value)">
                            <input type="hidden" id="categoria_libro_edit">

                            <div id="sugerencias_edit"
                                 style="text-align:left; max-height:200px; margin-top: 5px;"></div>

                            <div id="categoriasSeleccionadas_edit" class="mt-2">
                                ${
                                    libro.categorias_nombres && window.categoriasSeleccionadas.length > 0
                                        ? libro.categorias_nombres.split(',').map((cat, index) => {
                                            const idCat = window.categoriasSeleccionadas[index];
                                            return `
                                                <span style="
                                                    display:inline-flex;align-items:center;
                                                    background:#e8f5e9;color:#2e7d32;
                                                    padding:6px 12px;border-radius:30px;
                                                    font-size:14px;border:1px solid #c8e6c9;
                                                    margin-right:5px;margin-bottom:5px;">
                                                    ${cat.trim()}
                                                    <span onclick="eliminarCategoriaEdit(${idCat}, this)"
                                                          style="margin-left:8px;font-size:16px;cursor:pointer;">&times;</span>
                                                </span>
                                            `;
                                        }).join('')
                                        : ''
                                }
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad_edit"
                                   min="0" value="${libro.cantidad_libro}" required>
                        </div>
                    </form>
                `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    // Cargar los IDs en el input hidden
                    document.getElementById('categoria_libro_edit').value =
                        JSON.stringify(window.categoriasSeleccionadas);
                },
                preConfirm: () => {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('titulo', $('#titulo_edit').val().trim());
                    formData.append('autor', $('#autor_edit').val().trim());
                    formData.append('ISBN', $('#ISBN_edit').val().trim());
                    formData.append('categorias', $('#categoria_libro_edit').val());
                    formData.append('cantidad', $('#cantidad_edit').val());

                    return formData;
                }
            }).then(result => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: '../controllers/editar_Libro.php',
                    type: 'POST',
                    data: result.value,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('✅ Éxito', res.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('⚠️ Atención', res.message, 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('❌ Error', 'Error en el servidor', 'error');
                        console.error(error, xhr.responseText);
                    }
                });
            });
        },
        error: function() {
            Swal.fire('❌ Error', 'No se pudo cargar la información del libro', 'error');
        }
    });
} 
        
// Funciones auxiliares para edición
function buscarCategoriaEdit(texto) {
    if (texto.length < 2) {
        document.getElementById('sugerencias_edit').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscarCategoria.php',
        type: 'POST',
        dataType: 'json',
        data: { query: texto },
        success: function(categorias) {
            let html = '<ul class="list-group">';
            if (categorias.length > 0) {
                categorias.forEach(categoria => {
                    html += `
                        <li class="list-group-item list-group-item-action" 
                            style="cursor: pointer;" 
                            onclick="seleccionarCategoriaEdit(${categoria.id}, '${categoria.nombre_categoria.replace(/'/g, "\\'")}')">
                            ${categoria.nombre_categoria}
                        </li>
                    `;
                });
                html += '</ul>';
            } else {
                html += `
                    <div class="alert alert-info mb-0">
                        <small>No se encontró la categoría "${texto}"</small>
                    </div>
                `;
            }
            document.getElementById('sugerencias_edit').innerHTML = html;
        }
    });
}

function seleccionarCategoriaEdit(id, nombre) {
    id = parseInt(id);

    // asegurar que el array siempre sea numero
    window.categoriasSeleccionadas = Array.isArray(window.categoriasSeleccionadas)
        ? window.categoriasSeleccionadas.map(Number)
        : window.categoriasSeleccionadas.toString().split(',').map(e => parseInt(e.trim()));

    // evitar repetidos
    if (window.categoriasSeleccionadas.includes(id)) {
        document.getElementById('sugerencias_edit').innerHTML = '';
        document.getElementById('busquedaCategoria_edit').value = '';
        return;
    }

    window.categoriasSeleccionadas.push(id);
    document.getElementById('categoria_libro_edit').value = JSON.stringify(window.categoriasSeleccionadas);

    const contenedor = document.getElementById('categoriasSeleccionadas_edit');
    const chip = document.createElement('span');
    chip.style.cssText = `
        display: inline-flex;
        align-items: center;
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 14px;
        border: 1px solid #c8e6c9;
        margin-right: 5px;
        margin-bottom: 5px;
    `;
    chip.innerHTML = `
        ${nombre}
        <span onclick="eliminarCategoriaEdit(${id}, this)" 
              style="margin-left: 8px; font-size: 16px; cursor: pointer;">&times;</span>
    `;

    contenedor.appendChild(chip);

    document.getElementById('sugerencias_edit').innerHTML = '';
    document.getElementById('busquedaCategoria_edit').value = '';
}

function eliminarCategoriaEdit(id, elemento) {
    id = parseInt(id);

    // asegurar que el array sea numerico 
    window.categoriasSeleccionadas = window.categoriasSeleccionadas
        .map(Number)
        .filter(catId => catId !== id);

    document.getElementById('categoria_libro_edit').value =
        JSON.stringify(window.categoriasSeleccionadas);

    elemento.parentNode.remove();
}
</script>

<script>
function eliminarLibro(id) {
  Swal.fire({
    title: "¿Deseas eliminar el libro?",
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

<script>
function activarLibro(id) {
  Swal.fire({
    title: "¿Deseas Activar el libro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, activar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Libro Activado!",
        text: "El libro ha sido activado exitosamente.",
        icon: "success",
        timer: 1500,      // el tiempo que se demora en cerrar el alert 
        showConfirmButton: false
      }).then(() => {
        // Redirige al controlador de eliminar  cuando cierra el alert 
        window.location.href = "../controllers/activarLibro.php?id=" + id;
      });
    }
  });
}
</script>

  </body>
  <!--end::Body-->
</html>
