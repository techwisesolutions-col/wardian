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
   <body class="sidebar-main-active right-column-fixed">
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
         <?php
         $cliente_id = isset($_GET['cliente_id']) ? htmlspecialchars($_GET['cliente_id']) : '';
         ?>

         <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                     <div class="col-sm-12 col-lg-12">

                     <?php if (isset($_GET['error']) && $_GET['error'] == 'missing_fields'): ?>
                        <div class="alert alert-danger" role="alert">
                           <div class="iq-alert-icon">
                                 <i class="ri-alert-line"></i>
                           </div>
                           <div class="iq-alert-text">
                                 <strong>Error:</strong> Faltan los siguientes campos obligatorios: 
                                 <b><?php echo htmlspecialchars($_GET['fields']); ?></b>.
                           </div>
                        </div>
                     <?php endif; ?>

                     <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                        <div class="alert alert-success" role="alert">
                           <div class="iq-alert-icon">
                                 <i class="ri-check-line"></i>
                           </div>
                           <div class="iq-alert-text">
                                 <strong>¡Éxito!</strong> Acceso registrado con éxito para el Cliente ID: <?php echo htmlspecialchars($_GET['cliente_id']); ?>.
                           </div>
                        </div>
                     <?php endif; ?>

                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                                 <div class="iq-header-title">
                                    <h4 class="card-title">Registrar Acceso para Cliente ID: <?php echo $cliente_id; ?></h4>
                                 </div>
                           </div>
                           <div class="iq-card-body">
                                 <form method="POST" action="../api/form-client-access.php" novalidate>
                                    <input type="hidden" id="cliente_id" name="cliente_id" value="<?php echo $cliente_id; ?>">

                                    <div class="form-row">
                                       <div class="col-md-6 mb-3">
                                             <label for="aplicativo">Aplicativo</label>
                                             <select class="form-control" id="aplicativo" name="aplicativo" required>
                                                <option value="" disabled selected>Seleccione un aplicativo</option>
                                                <?php
                                                require '../config/conexion.php';
                                                $query = "SELECT nombre FROM aplicativos";
                                                $result = $conn->query($query);
                                                while ($row = $result->fetch_assoc()):
                                                ?>
                                                   <option value="<?php echo htmlspecialchars($row['nombre']); ?>">
                                                         <?php echo htmlspecialchars($row['nombre']); ?>
                                                   </option>
                                                <?php endwhile; ?>
                                             </select>
                                       </div>

                                       <div class="col-md-6 mb-3">
                                             <label for="usuario">Usuario</label>
                                             <input type="text" class="form-control" id="usuario" name="usuario" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="clave">Clave</label>
                                             <input type="password" class="form-control" id="clave" name="clave" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="url">URL</label>
                                             <input type="url" class="form-control" id="url" name="url" required>
                                       </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit">Registrar Acceso</button>
                                 </form>
                           </div>
                        </div>
                     </div>
               </div>
          


               <div class="row">
                  <div class="col-sm-12">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Accesos Registrados</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                              <div class="table-responsive">
                                 <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                       <div id="user_list_datatable_info" class="dataTables_filter">
                                          <form class="mr-3 position-relative">
                                             <div class="form-group mb-0">
                                                <input type="search" class="form-control" id="exampleInputSearch" placeholder="Buscar..." aria-controls="user-list-table">
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                       <div class="user-list-files d-flex float-right">
                                          <a class="iq-bg-primary" href="javascript:void();" >
                                             Imprimir
                                          </a>
                                          <a class="iq-bg-primary" href="javascript:void();">
                                             Excel
                                          </a>
                                          <a class="iq-bg-primary" href="javascript:void();">
                                             PDF
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                                 <?php
                                 require '../config/conexion.php'; // Conexión a la base de datos

                                 $sql = "SELECT * FROM `accesos` LEFT JOIN `aplicativos` ON `accesos`.`aplicativo` = `aplicativos`.`nombre` WHERE `accesos`.`cliente_id` = '$cliente_id'";
                                 $result = $conn->query($sql);
                                 ?>

                                 <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                                    <thead>
                                       <tr>
                                             <th>Logo</th>
                                             <th>Aplicativo</th>
                                             <th>Usuario</th>
                                             <th>Acciones</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       if ($result->num_rows > 0) {
                                             while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                   <td class='text-center'><img class='rounded img-fluid avatar-40' src='{$row['logo_url']}' alt='profile'></td>
                                                   <td>{$row['aplicativo']}</td>
                                                   <td>{$row['usuario']}</td>
                                                   <td>
                                                         <div class='flex align-items-center list-user-action'>
                                                            <a class='iq-bg-primary' data-toggle='tooltip' data-placement='top' title='Acceder' href=''><i class='ri-login-circle-line'></i></a>
                                                         </div>
                                                   </td>
                                                </tr>";
                                             }
                                       } else {
                                             echo "<tr><td colspan='9' class='text-center'>No se encontraron registros...</td></tr>";
                                       }
                                       ?>
                                    </tbody>
                                 </table>

                                 <?php
                                 $conn->close();
                                 ?>

                              </div>
                                 <div class="row justify-content-between mt-3">
                                    <div id="user-list-page-info" class="col-md-6">
                                       <span>Showing 1 to 5 of 5 entries</span>
                                    </div>
                                    <div class="col-md-6">
                                       <nav aria-label="Page navigation example">
                                          <ul class="pagination justify-content-end mb-0">
                                             <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                             </li>
                                             <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                             <li class="page-item"><a class="page-link" href="#">2</a></li>
                                             <li class="page-item"><a class="page-link" href="#">3</a></li>
                                             <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
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

      <script>
         // Función para convertir texto a mayúsculas
         function convertirAMayusculas(input) {
            input.value = input.value.toUpperCase();
         }
      </script>
   </body>
</html>