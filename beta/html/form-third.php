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
                                 <strong>¡Éxito!</strong> Tercero registrado con éxito.
                           </div>
                        </div>
                     <?php endif; ?>

                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                                 <div class="iq-header-title">
                                    <h4 class="card-title">Terceros</h4>
                                 </div>
                           </div>
                           <div class="iq-card-body">
                                 <form method="POST" action="../api/form-third.php" novalidate>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="name_or_business_name">Nombre o Razón Social</label>
            <input type="text" class="form-control" id="name_or_business_name" name="name_or_business_name" required>
        </div>
      
       <div class="col-md-6 mb-3">
            <label for="document_type">Tipo</label>
            <select class="form-control" id="type" name="type" required>
                <option value="" disabled selected>Seleccione...</option>
                <option value="ES PERSONAL">ES PERSONA</option>
                <option value="EMPRESA">EMPRESA</option>
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="document_type">Tipo de Documento</label>
            <select class="form-control" id="document_type" name="document_type" required>
                <option value="" disabled selected>Seleccione...</option>
                <option value="NIT">NIT</option>
                <option value="RUT">RUT</option>
                <option value="Cédula">Cédula de Ciudadanía</option>
                <option value="DNI">Documento Nacional de Identidad (DNI)</option>
                <option value="Pasaporte">Pasaporte</option>
                <option value="PEP">Permiso Especial de Permanencia (PEP)</option>
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
                                             <label for="nit">Numero de Documento</label>
                                             <input type="text" class="form-control" id="nit" name="nit" oninput="calcularDV()" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="dv">Dígito de Verificación (DV)</label>
                                             <input type="text" class="form-control" id="dv" name="dv" readonly>
                                       </div>
        
        <div class="col-md-6 mb-3">
            <label for="address">Dirección</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="city">Ciudad</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="state_or_region">Departamento o Estado</label>
            <input type="text" class="form-control" id="state_or_region" name="state_or_region" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="postal_code">Código Postal</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code">
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="country">País</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="email">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="phone">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="tax_regime">Responsabilidad fiscal <a data-toggle='tooltip' 
                                                               data-placement='top' title='Verifica la responsabilidad en el RUT de tu cliente, mínimo asignar R-99-PN' href='#'>
                                                                  <i class="ri-alert-line"></i> Importante
                                                            </a></label>
            <select class="form-control" id="tax_regime" name="tax_regime">
              <option value="O-13">O-13 - Gran contribuyente</option>
              <option value="O-15">O-15 - Autorretenedor</option> 
              <option value="O-23">O-23 - Agente de retención IVA</option> 
              <option value="O-47">O-47 - Régimen simple de tributación</option> 
              <option value="R-99-PN" selected>R-99-PN - No aplica - Otros</option> 
            </select>
        </div>
      
        <div class="col-md-6 mb-3">
            <label for="tax_regime">Tipo de régimen IVA</label>
            <select class="form-control" id="type_iva" name="type_iva">
              <option value="No reponsable de IVA">No reponsable de IVA</option>
              <option value="Responsable de IVA">Responsable de IVA</option> 
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="payment_method">Forma de Pago</label>
            <select class="form-control" id="payment_method" name="payment_method">
                <option value="Efectivo" selected>Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Crédito">Crédito</option>
            </select>
        </div>
        
        <div class="col-md-6 mb-3">
            <label for="credit_days">Días de Crédito</label>
            <input type="number" class="form-control" id="credit_days" name="credit_days" value="0">
        </div>
    </div>
    
    <button class="btn btn-primary" type="submit">Registrar Tercero</button>
</form>

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