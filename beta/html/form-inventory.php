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

                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                                 <div class="iq-header-title">
                                    <h4 class="card-title">Nuevo Producto / Servicio</h4>
                                 </div>
                           </div>
                           <div class="iq-card-body">
                                 <form method="POST" action="../api/electronic-billing/form-inventory.php" enctype="multipart/form-data" novalidate>

    <!-- Tipo de Producto -->
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="product_type">Tipo <span class="text-danger">*</span></label>
            <select class="form-control" id="product_type" name="product_type" required>
                <option value="" disabled selected>Seleccione...</option>
                <option value="product">Producto</option>
                <option value="service">Servicio</option>
            </select>
        </div>

        <!-- Nombre del Producto -->
        <div class="col-md-6 mb-3">
            <label for="product_name">Nombre del Producto <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="product_name" name="product_name" required oninput="convertirAMayusculas(this)">
        </div>
    </div>

    <!-- Imágenes -->
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <label for="product_images">Imágenes (Máximo 5 de 500px de ancho por 375px de alto) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*" required>
            <small class="form-text text-muted">Puedes adjuntar hasta 5 imágenes. El tamaño máximo por imagen es de 500px de ancho y 375px de alto.</small>
        </div>
    </div>

    <!-- Impuesto y IVA -->
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="tax_charge">Impuesto Cargo <span class="text-danger">*</span></label>
            <select class="form-control" id="tax_charge" name="tax_charge" required>
                <option value="" disabled selected>Seleccione...</option>
                <option value="IVA19">IVA 19%</option>
                <option value="IVA5">IVA 5%</option>
                <option value="Impocosumo 8%">Impocosumo 8%</option>
                <option value="Impocosumo por valor">Impocosumo por valor</option>
                <option value="IVA8">IVA 0%</option>
                <option value="IVA16">IVA 16%</option>
                <!-- Aquí puedes agregar más impuestos si es necesario -->
            </select>
        </div>

        <!-- Detalle de Producto -->
        <div class="col-md-6 mb-3">
            <label for="product_detail">Detalle del Producto</label>
            <textarea class="form-control" id="product_detail" name="product_detail" rows="3" oninput="convertirAMayusculas(this)"></textarea>
        </div>
    </div>

    <!-- Unidad del Producto -->
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="product_unit">Unidad <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="product_unit" name="product_unit" required>
        </div>

        <!-- Descripción Larga -->
        <div class="col-md-6 mb-3">
            <label for="long_description">Descripción Larga <span class="text-danger">*</span></label>
            <textarea class="form-control" id="long_description" name="long_description" rows="4" required></textarea>
        </div>
    </div>

    <!-- Listas de Precios de Venta -->
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="price_list">Precio de Venta <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="price_list" name="price_list" required min="0" step="0.01">
        </div>

        <!-- ¿El IVA está incluido en el precio de venta? -->
        <div class="col-md-6 mb-3">
            <label for="include_iva">¿El IVA está incluido en el precio de venta? <span class="text-danger">*</span></label>
            <select class="form-control" id="include_iva" name="include_iva" required>
                <option value="" disabled selected>Seleccione...</option>
                <option value="yes">Sí</option>
                <option value="no">No</option>
            </select>
        </div>
    </div>

    <!-- Botón para Registrar el Producto -->
    <button class="btn btn-primary" type="submit">Registrar Producto</button>
</form>

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

      <script>
         // Función para convertir texto a mayúsculas
         function convertirAMayusculas(input) {
            input.value = input.value.toUpperCase();
         }

         // Función para calcular el DV basado en el NIT ingresado
         function calcularDV() {
            const nit = document.getElementById('nit').value;
            const dv = document.getElementById('dv');

            if (!nit) {
               dv.value = '';
               return;
            }

            const pesos = [3, 7, 13, 17, 19, 23, 29, 37, 41];
            let suma = 0;

            // Iterar sobre cada dígito del NIT desde el final hacia el inicio
            for (let i = 0; i < nit.length; i++) {
               const digito = parseInt(nit.charAt(nit.length - 1 - i), 10);
               if (!isNaN(digito)) {
                     suma += digito * pesos[i];
               }
            }

            const resto = suma % 11;
            dv.value = resto > 1 ? 11 - resto : resto;
         }

      </script>
   </body>
</html>