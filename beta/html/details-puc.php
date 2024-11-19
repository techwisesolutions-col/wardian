<!doctype html>
<?php 
$clase = isset($_GET['clase']) ? $_GET['clase'] : null;
$grupo = isset($_GET['grupo']) ? $_GET['grupo'] : null;
$cuenta = isset($_GET['cuenta']) ? $_GET['cuenta'] : null;
$subcuenta = isset($_GET['subcuenta']) ? $_GET['subcuenta'] : null;

require '../config/conexion.php'; // Conexión a la base de datos

if ($clase) {
    // Consulta SQL para obtener el registro específico
    $sql = "SELECT * FROM `puc_colombia` WHERE `clase` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $clase);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pasar cada columna a una variable
        $nombre_clase = $data['nombre'];

        // Ahora puedes usar las variables como desees
    } else {
        echo "No se encontró un tercero para la factura con el ID proporcionado.";
    }
} else {
    echo "El parámetro id_third es requerido.";
}

if ($grupo) {
    // Consulta SQL para obtener el registro específico
    $sql = "SELECT * FROM `puc_colombia` WHERE `grupo` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $grupo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pasar cada columna a una variable
        $nombre_grupo = $data['nombre'];

        // Ahora puedes usar las variables como desees
    } else {
        echo "No se encontró un tercero para la factura con el ID proporcionado.";
    }
} else {
    echo "El parámetro id_third es requerido.";
}

if ($cuenta) {
    // Consulta SQL para obtener el registro específico
    $sql = "SELECT * FROM `puc_colombia` WHERE `cuenta` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $cuenta);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pasar cada columna a una variable
        $nombre_cuenta = $data['nombre'];

        // Ahora puedes usar las variables como desees
    } else {
        echo "No se encontró un tercero para la factura con el ID proporcionado.";
    }
} else {
    echo "El parámetro id_third es requerido.";
}

if ($subcuenta) {
    // Consulta SQL para obtener el registro específico
    $sql = "SELECT * FROM `puc_colombia` WHERE `subcuenta` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $subcuenta);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pasar cada columna a una variable
        $nombre_subcuenta = $data['nombre'];

        // Ahora puedes usar las variables como desees
    } else {
        echo "No se encontró un tercero para la factura con el ID proporcionado.";
    }
} else {
    echo "El parámetro id_third es requerido.";
}
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
                                    <h4 class="card-title">Detalles</h4>
                                 </div>
                           </div>
                           <div class="iq-card-body">
                                 <form method="POST" action="../api/details-auxiliar-puc.php" novalidate>
                                    <div class="form-row">
                                       <div class="col-md-6 mb-3">
                                             <label for="empresa">Clase</label>
                                             <input type="text" class="form-control" id="clase" name="clase" value="<?php echo $nombre_clase; ?>" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="nit">Grupo</label>
                                             <input type="text" class="form-control" id="grupo" name="grupo" value="<?php echo $nombre_grupo; ?>" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="nit">Cuenta</label>
                                             <input type="text" class="form-control" id="cuenta" name="cuenta" value="<?php echo $nombre_cuenta; ?>" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="nit">Subcuenta</label>
                                             <input type="text" class="form-control" id="subcuenta" name="subcuenta" value="<?php echo $nombre_subcuenta; ?>" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="nit">Código</label>
                                             <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $nombre_auxiliar; ?>" required>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                             <label for="nit">Auxiliar</label>
                                             <input type="text" class="form-control" id="auxiliar" name="auxiliar" value="<?php echo $nombre_auxiliar; ?>" required>
                                       </div>
                                      
                                       <input type="hidden" id="nivel" name="nivel" value="Auxiliar" required>
                                      
                                    </div>
                                    <button class="btn btn-primary" type="submit">Guardar Cambios</button>
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