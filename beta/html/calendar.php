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

                        <?php if (isset($_GET['error']) && $_GET['error'] == 'nit_exists'): ?>
                           <div class="alert alert-danger" role="alert">
                              <div class="iq-alert-icon">
                                    <i class="ri-alert-line"></i>
                              </div>
                              <div class="iq-alert-text">
                                    <strong>Error:</strong> El NIT ingresado ya existe en el sistema. Inténtelo con otro NIT.
                              </div>
                           </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                           <div class="alert alert-success" role="alert">
                              <div class="iq-alert-icon">
                                    <i class="ri-check-line"></i>
                              </div>
                              <div class="iq-alert-text">
                                    <strong>¡Éxito!</strong> Cliente registrado con éxito.
                              </div>
                           </div>
                        <?php endif; ?>

                         <!-- Calendario Tributario -->
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Calendario Tributario DIAN Colombia 2024</h4>
                           </div>
                        </div>

                        <!-- Contenido del calendario -->
                        <div class="iq-card-body">
                           <div class="calendario-tributario">
                              <div class="mes">
                                 <h2>Enero</h2>
                                 <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>20 de Enero: Declaración de Renta - Grandes Contribuyentes</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>30 de Enero: Pago de Retenciones en la Fuente</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                 </ul>
                              </div>

                              <div class="mes mt-4">
                                 <h2>Febrero</h2>
                                 <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>10 de Febrero: Pago del IVA Bimestral</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>28 de Febrero: Declaración de Renta - Personas Jurídicas</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                 </ul>
                              </div>

                              <div class="mes mt-4">
                                 <h2>Marzo</h2>
                                 <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>15 de Marzo: Declaración del Impuesto sobre la Renta</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                       <span>25 de Marzo: Pago del Primer Cuota del Impuesto de Industria y Comercio</span>
                                       <i class="ri-calendar-line text-muted"></i>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Page Content END -->

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
            // Función para enviar el calendario por WhatsApp
            document.getElementById("whatsappButton").addEventListener("click", function () {
               const message = "Calendario Tributario DIAN Colombia 2024:\n\n" +
                  "Enero:\n" +
                  "- 20 de Enero: Declaración de Renta - Grandes Contribuyentes\n" +
                  "- 30 de Enero: Pago de Retenciones en la Fuente\n\n" +
                  "Febrero:\n" +
                  "- 10 de Febrero: Pago del IVA Bimestral\n" +
                  "- 28 de Febrero: Declaración de Renta - Personas Jurídicas\n\n" +
                  "Marzo:\n" +
                  "- 15 de Marzo: Declaración del Impuesto sobre la Renta\n" +
                  "- 25 de Marzo: Pago del Primer Cuota del Impuesto de Industria y Comercio";
   
               const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
               window.open(url, "_blank");
            });
         </script>
   </body>
</html>