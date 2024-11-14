<?php
require '../config/conexion.php'; // Conexión a la base de datos

// Definir la cantidad de registros por página
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$busqueda = isset($_GET['search']) ? $_GET['search'] : '';

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta SQL principal con el nuevo SELECT y búsqueda
$sql = "SELECT 
            invoice_lines.tax_percent, 
            SUM(invoice_lines.line_extension_amount) AS total_sin_iva, 
            SUM(invoice_lines.tax_amount) AS total_iva, 
            SUM(invoice_lines.line_extension_amount + invoice_lines.tax_amount) AS total_con_iva, 
            COUNT(*) AS cant,
            clientes.file,
            clientes.empresa
        FROM 
            invoice_lines 
        LEFT JOIN 
            invoices ON invoice_lines.invoice_id = invoices.id 
        LEFT JOIN 
            clientes ON invoices.client_id = clientes.id 
        WHERE 
            clientes.empresa LIKE ? 
        GROUP BY 
            clientes.file, 
            clientes.empresa, 
            invoice_lines.tax_percent 
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$busqueda_param = '%' . $busqueda . '%';
$stmt->bind_param('sii', $busqueda_param, $offset, $registros_por_pagina);
$stmt->execute();
$result = $stmt->get_result();

// Consulta para obtener el número total de registros para la paginación
$total_sql = "SELECT 
                  COUNT(DISTINCT clientes.file, clientes.empresa, invoice_lines.tax_percent) AS total 
              FROM 
                  invoice_lines 
              LEFT JOIN 
                  invoices ON invoice_lines.invoice_id = invoices.id 
              LEFT JOIN 
                  clientes ON invoices.client_id = clientes.id 
              WHERE 
                  clientes.empresa LIKE ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param('s', $busqueda_param);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_registros = $total_result->fetch_assoc()['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<!doctype html>
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
                                 <h4 class="card-title">Detalle De Resumen De Venta</h4>
                              </div>
                        </div>
                        <div class="iq-card-body">
                              <div class="table-responsive">
                                 <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                          <form class="mr-3 position-relative" method="GET" action="">
                                             <div class="form-group mb-0">
                                                <input type="search" class="form-control" id="exampleInputSearch" 
                                                         name="search" placeholder="Buscar..." 
                                                         value="<?php echo htmlspecialchars($busqueda); ?>">
                                             </div>
                                          </form>
                                    </div>
                                 </div>

                                 <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid">
                                    <thead>
                                          <tr>
                                             <th>Tipo De Impuesto</th>
                                             <th>Base</th>
                                             <th>IVA</th>
                                             <th>Facturas</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          <?php if ($result->num_rows > 0): ?>
                                             <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                      <td>%<?php echo $row['tax_percent']; ?></td>
                                                      <td>$<?php echo number_format($row['total_sin_iva'], 2, ',', '.'); ?></td>
                                                      <td>$<?php echo number_format($row['total_iva'], 2, ',', '.'); ?></td>
                                                      <td><?php echo number_format($row['cant'], 0, ',', '.'); ?></td>
                                                </tr>
                                             <?php endwhile; ?>
                                          <?php else: ?>
                                             <tr><td colspan="4" class="text-center">No se encontraron registros...</td></tr>
                                          <?php endif; ?>
                                    </tbody>
                                 </table>

                                 <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                          <li class="page-item <?php if ($pagina_actual <= 1) echo 'disabled'; ?>">
                                             <a class="page-link" href="?page=<?php echo $pagina_actual - 1; ?>&search=<?php echo $busqueda; ?>">
                                                Previous
                                             </a>
                                          </li>
                                          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                             <li class="page-item <?php if ($i == $pagina_actual) echo 'active'; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $busqueda; ?>">
                                                      <?php echo $i; ?>
                                                </a>
                                             </li>
                                          <?php endfor; ?>
                                          <li class="page-item <?php if ($pagina_actual >= $total_paginas) echo 'disabled'; ?>">
                                             <a class="page-link" href="?page=<?php echo $pagina_actual + 1; ?>&search=<?php echo $busqueda; ?>">
                                                Next
                                             </a>
                                          </li>
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
   <!-- Wrapper END -->
     <!-- Footer -->
      <footer class="iq-footer">
         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-6">
                  <ul class="list-inline mb-0">
                     <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                     <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                  </ul>
               </div>
               <div class="col-lg-6 text-right">
                  Copyright 2020 <a href="#">Nexo</a> All Rights Reserved.
               </div>
            </div>
         </div>
      </footer>
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