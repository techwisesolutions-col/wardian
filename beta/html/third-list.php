<!doctype html>
<?php
require '../config/conexion.php'; // Conexión a la base de datos

// Definir la cantidad de registros por página
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$busqueda = isset($_GET['search']) ? $_GET['search'] : '';

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta SQL con búsqueda y paginación
$sql = "SELECT * FROM clientes WHERE empresa LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$busqueda_param = '%' . $busqueda . '%';
$stmt->bind_param('sii', $busqueda_param, $offset, $registros_por_pagina);
$stmt->execute();
$result = $stmt->get_result();

// Obtener el número total de registros para la paginación
$total_sql = "SELECT COUNT(*) AS total FROM clientes WHERE empresa LIKE ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param('s', $busqueda_param);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_registros = $total_result->fetch_assoc()['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);
?>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>WARDIAN - SYSTEM</title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="images/favicon.ico" />
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="css/typography.css">
      <!-- Style CSS -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="css/responsive.css">
   </head>
   <body>
      <!-- loader Start -->
      <div id="loading">
         <div id="loading-center">
         </div>
      </div>
      <!-- loader END -->
      <!-- Wrapper Start -->
      <div class="wrapper">
      <!-- Sidebar  -->
      <div class="iq-sidebar">
            <div class="iq-navbar-logo d-flex justify-content-between">
               <a href="index.html" class="header-logo">
                  <img src="images/logo.png" class="img-fluid rounded" alt="">
                  <span>Nexo</span>
               </a>
               <div class="iq-menu-bt align-self-center">
                  <div class="wrapper-menu">
                     <div class="main-circle"><i class="ri-menu-line"></i></div>
                     <div class="hover-circle"><i class="ri-close-fill"></i></div>
                  </div>
               </div>
            </div>
            <div id="sidebar-scrollbar">
                <?php include './nav.php'; ?>
               <div class="p-3"></div>
            </div>
         </div>
         <!-- TOP Nav Bar -->
         <?php include './header.php'; ?>
         <!-- TOP Nav Bar END -->

         <!-- Page Content  -->
         <div id="content-page" class="content-page">
         <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-12">
                  <?php if (isset($_GET['error']) && $_GET['error'] == 'missing_id'): ?>
                     <div class="alert alert-danger" role="alert">
                        <div class="iq-alert-icon">
                              <i class="ri-alert-line"></i>
                        </div>
                        <div class="iq-alert-text">
                              <strong>Error:</strong> No se proporcionó un ID válido para eliminar.
                        </div>
                     </div>
                  <?php endif; ?>

                  <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
                     <div class="alert alert-danger" role="alert">
                        <div class="iq-alert-icon">
                              <i class="ri-alert-line"></i>
                        </div>
                        <div class="iq-alert-text">
                              <strong>Error:</strong> No se pudo eliminar el cliente. Inténtalo de nuevo.
                        </div>
                     </div>
                  <?php endif; ?>

                  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                     <div class="alert alert-success" role="alert">
                        <div class="iq-alert-icon">
                              <i class="ri-check-line"></i>
                        </div>
                        <div class="iq-alert-text">
                              <strong>¡Éxito!</strong> Cliente eliminado con éxito.
                        </div>
                     </div>
                  <?php endif; ?>

                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Lista De Terceros</h4>
                              </div>
                        </div>
                        <div class="iq-card-body">
                              <div class="table-responsive">
                                 <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                          <form method="GET" class="mr-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="search" class="form-control" name="search" id="exampleInputSearch" placeholder="Buscar..." aria-controls="user-list-table" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    

                    <?php
require '../config/conexion.php'; // Conexión a la base de datos

// Paginación
$limit = 5; // Número de registros por página
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Desplazamiento para la consulta

// Búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = $search ? "WHERE name_or_business_name LIKE '%$search%' OR nit LIKE '%$search%' OR email LIKE '%$search%'" : '';

// Contar total de registros
$count_sql = "SELECT COUNT(*) as total FROM invoice_recipients $search_query";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit); // Número total de páginas

// Consulta con paginación y búsqueda
$sql = "SELECT id, name_or_business_name, document_type, nit, dv, address, city, state_or_region, postal_code, country, email, phone, tax_regime, payment_method, credit_days, registration_date FROM invoice_recipients $search_query LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
    <thead>
        <tr>
            <th class='text-center'>Razón Social</th>
            <th class='text-center'>Tipo de Documento</th>
            <th class='text-center'>NIT</th>
            <th class='text-center'>DV</th>
            <th class='text-center'>Dirección</th>
            <th class='text-center'>Ciudad</th>
            <th class='text-center'>Estado/Región</th>
            <th class='text-center'>Código Postal</th>
            <th class='text-center'>País</th>
            <th class='text-center'>Correo</th>
            <th class='text-center'>Teléfono</th>
            <th class='text-center'>Régimen Tributario</th>
            <th class='text-center'>Método de Pago</th>
            <th class='text-center'>Días de Crédito</th>
            <th class='text-center'>Fecha de Registro</th>
            <th class='text-center'>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['name_or_business_name']}</td>
                    <td>{$row['document_type']}</td>
                    <td>{$row['nit']}</td>
                    <td>{$row['dv']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['city']}</td>
                    <td>{$row['state_or_region']}</td>
                    <td>{$row['postal_code']}</td>
                    <td>{$row['country']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['tax_regime']}</td>
                    <td>{$row['payment_method']}</td>
                    <td>{$row['credit_days']}</td>
                    <td>{$row['registration_date']}</td>
                    <td>
                        <div class='flex align-items-center list-user-action'>
                            <a class='iq-bg-primary' data-toggle='tooltip' data-placement='top' title='Editar' href=''><i class='ri-pencil-line'></i></a>
                            <a class='iq-bg-primary' data-toggle='tooltip' data-placement='top' title='Eliminar' href=''><i class='ri-delete-bin-line'></i></a>
                        </div>
                        </div>
                        
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='15' class='text-center'>No se encontraron registros...</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
?>

<div class="row justify-content-between mt-3">
    <div id="user-list-page-info" class="col-md-6">
        <span>Mostrando <?php echo ($offset + 1) . ' a ' . min($offset + $limit, $total_records); ?> de <?php echo $total_records; ?> entradas</span>
    </div>
    <div class="col-md-6">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end mb-0">
                <?php
                // Paginación
                $prev_page = ($page > 1) ? $page - 1 : 1;
                $next_page = ($page < $total_pages) ? $page + 1 : $total_pages;

                // Botón Anterior
                echo "<li class='page-item " . ($page == 1 ? 'disabled' : '') . "'><a class='page-link' href='?page=$prev_page&search=$search'>Anterior</a></li>";

                // Botones de páginas
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'><a class='page-link' href='?page=$i&search=$search'>$i</a></li>";
                }

                // Botón Siguiente
                echo "<li class='page-item " . ($page == $total_pages ? 'disabled' : '') . "'><a class='page-link' href='?page=$next_page&search=$search'>Siguiente</a></li>";
                ?>
            </ul>
        </nav>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

            </div>
         </div>
      </div>
   <!-- Wrapper END -->
     
    <!-- Footer -->
    <?php include './footer.php'; ?>
    <!-- Footer END -->
    
    <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- Appear JavaScript -->
      <script src="js/jquery.appear.js"></script>
      <!-- Countdown JavaScript -->
      <script src="js/countdown.min.js"></script>
      <!-- Counterup JavaScript -->
      <script src="js/waypoints.min.js"></script>
      <script src="js/jquery.counterup.min.js"></script>
      <!-- Wow JavaScript -->
      <script src="js/wow.min.js"></script>
      <!-- Apexcharts JavaScript -->
      <script src="js/apexcharts.js"></script>
      <!-- Slick JavaScript -->
      <script src="js/slick.min.js"></script>
      <!-- Select2 JavaScript -->
      <script src="js/select2.min.js"></script>
      <!-- Owl Carousel JavaScript -->
      <script src="js/owl.carousel.min.js"></script>
      <!-- Magnific Popup JavaScript -->
      <script src="js/jquery.magnific-popup.min.js"></script>
      <!-- Smooth Scrollbar JavaScript -->
      <script src="js/smooth-scrollbar.js"></script>
      <!-- lottie JavaScript -->
      <script src="js/lottie.js"></script>
      <!-- am core JavaScript -->
      <script src="js/core.js"></script>
      <!-- am charts JavaScript -->
      <script src="js/charts.js"></script>
      <!-- am animated JavaScript -->
      <script src="js/animated.js"></script>
      <!-- am kelly JavaScript -->
      <script src="js/kelly.js"></script>
      <!-- am maps JavaScript -->
      <script src="js/maps.js"></script>
      <!-- am worldLow JavaScript -->
      <script src="js/worldLow.js"></script>
      <!-- Style Customizer -->
      <script src="js/style-customizer.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>
   </body>
</html>