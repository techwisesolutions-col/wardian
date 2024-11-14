<?php
require '../config/conexion.php'; // Conexión a la base de datos

$date = date('Y-m');

// Definir la cantidad de registros por página
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$busqueda = isset($_GET['search']) ? $_GET['search'] : '';

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta SQL con búsqueda y paginación
$sql = "
    SELECT
    clientes.file,
    clientes.empresa,

    -- Renta - GRANDES Contribuyentes
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.date END) AS renta_grandes_contribuyentes_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_one END) AS renta_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_two END) AS renta_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_tree END) AS renta_period_tree,
    COALESCE(MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN 'Renta - GRANDES Contribuyentes' END), 'Sin datos') AS renta_grandes_contribuyentes_nombre,

    -- IVA (Bimestral o Cuatrimestral)
    COALESCE(
        MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.date END),
        MAX(CASE WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.date END)
    ) AS iva_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_one
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_one END) AS iva_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_two
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_two END) AS iva_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_tree
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_tree END) AS iva_period_tree,
    COALESCE(
        MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN 'IVA Bimestral - declaración y pago' END),
        MAX(CASE WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN 'IVA Cuatrimestral - declaración y pago' END),
        'Sin datos'
    ) AS iva_nombre,

    -- Retención en la Fuente
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.date END) AS retefuente_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_one END) AS retefuente_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_two END) AS retefuente_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_tree END) AS retefuente_period_tree,
    COALESCE(MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' END), 'Sin datos') AS retefuente_nombre,

    -- Sumar solo las líneas de factura correspondientes
    SUM(DISTINCT invoice_lines.line_extension_amount) AS total_sin_iva, 
    SUM(DISTINCT invoice_lines.tax_amount) AS total_iva, 
    SUM(DISTINCT (invoice_lines.line_extension_amount + invoice_lines.tax_amount)) AS total_con_iva

FROM
    invoice_lines
LEFT JOIN invoices ON invoice_lines.invoice_id = invoices.id
LEFT JOIN clientes ON invoices.client_id = clientes.id
LEFT JOIN tax_calendar ON MOD(clientes.nit, 10) = tax_calendar.last_digit_NIT
WHERE
    clientes.empresa LIKE ? 
    AND tax_calendar.date LIKE '%$date%'
GROUP BY
    clientes.file,
    clientes.empresa

LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$busqueda_param = '%' . $busqueda . '%';
$stmt->bind_param('sii', $busqueda_param, $offset, $registros_por_pagina);
$stmt->execute();
$result = $stmt->get_result();

// Obtener el número total de registros para la paginación
$total_sql = "
   SELECT
    clientes.file,
    clientes.empresa,

    -- Renta - GRANDES Contribuyentes
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.date END) AS renta_grandes_contribuyentes_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_one END) AS renta_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_two END) AS renta_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN tax_calendar.period_tree END) AS renta_period_tree,
    COALESCE(MAX(CASE WHEN tax_calendar.tax = 'Renta - GRANDES Contribuyentes' THEN 'Renta - GRANDES Contribuyentes' END), 'Sin datos') AS renta_grandes_contribuyentes_nombre,

    -- IVA (Bimestral o Cuatrimestral)
    COALESCE(
        MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.date END),
        MAX(CASE WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.date END)
    ) AS iva_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_one
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_one END) AS iva_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_two
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_two END) AS iva_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN tax_calendar.period_tree
             WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN tax_calendar.period_tree END) AS iva_period_tree,
    COALESCE(
        MAX(CASE WHEN tax_calendar.tax = 'IVA Bimestral - declaración y pago' THEN 'IVA Bimestral - declaración y pago' END),
        MAX(CASE WHEN tax_calendar.tax = 'IVA Cuatrimestral - declaración y pago' THEN 'IVA Cuatrimestral - declaración y pago' END),
        'Sin datos'
    ) AS iva_nombre,

    -- Retención en la Fuente
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.date END) AS retefuente_fecha,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_one END) AS retefuente_period_one,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_two END) AS retefuente_period_two,
    MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN tax_calendar.period_tree END) AS retefuente_period_tree,
    COALESCE(MAX(CASE WHEN tax_calendar.tax = 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' THEN 'RETENCIÓN EN LA FUENTE - Declaración mensual y pago' END), 'Sin datos') AS retefuente_nombre,

    -- Sumar solo las líneas de factura correspondientes
    SUM(DISTINCT invoice_lines.line_extension_amount) AS total_sin_iva, 
    SUM(DISTINCT invoice_lines.tax_amount) AS total_iva, 
    SUM(DISTINCT (invoice_lines.line_extension_amount + invoice_lines.tax_amount)) AS total_con_iva

FROM
    invoice_lines
LEFT JOIN invoices ON invoice_lines.invoice_id = invoices.id
LEFT JOIN clientes ON invoices.client_id = clientes.id
LEFT JOIN tax_calendar ON MOD(clientes.nit, 10) = tax_calendar.last_digit_NIT
WHERE
    clientes.empresa LIKE ? 
    AND tax_calendar.date LIKE '%$date%'
GROUP BY
    clientes.file,
    clientes.empresa
";

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
      <!-- Full calendar -->
      <link href='fullcalendar/core/main.css' rel='stylesheet' />
      <link href='fullcalendar/daygrid/main.css' rel='stylesheet' />
      <link href='fullcalendar/timegrid/main.css' rel='stylesheet' />
      <link href='fullcalendar/list/main.css' rel='stylesheet' />

      <link rel="stylesheet" href="css/flatpickr.min.css">

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
        
                  <div class="col-lg-12">
                     <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Impuestos Sugeridos</h4>
                           </div>
                           <div class="iq-card-header-toolbar d-flex align-items-center">
                              <div class="dropdown">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton5" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                    <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>Filtar Fecha</a>
                                    <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Generar Excel</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="iq-card-body">
                           <div class="table-responsive">
                              <table class="table">
                                        <thead>
                                            <tr>
                                                <th class='text-center'>Empresa</th>
                                                <?php 
                                                // Comprobar si al menos uno de los campos de impuestos tiene datos
                                                $mostrar_impuestos = false;

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        if (!empty($row['renta_grandes_contribuyentes_fecha']) || 
                                                            !empty($row['iva_fecha']) || 
                                                            !empty($row['retefuente_fecha'])) {
                                                            $mostrar_impuestos = true;
                                                            break; // Salir del bucle si se encuentra al menos un campo con datos
                                                        }
                                                    }
                                                    $result->data_seek(0); // Reiniciar el puntero del resultado
                                                }

                                                // Mostrar las cabeceras de impuestos si hay datos
                                                if ($mostrar_impuestos) {
                                                ?>
                                                    <th class='text-center'>Renta</th>
                                                    <th class='text-center'>IVA</th>
                                                <?php 
                                                } 
                                                ?>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                  <?php if ($result->num_rows > 0): ?>
                                      <?php while ($row = $result->fetch_assoc()): ?>
                                          <?php
                                          // Comprobar si al menos uno de los campos tiene datos
                                          $renta = $row['renta_grandes_contribuyentes_fecha'];
                                          $iva_fecha = $row['iva_fecha'];
                                          $retefuente = $row['retefuente_fecha'];

                                          // Definir imágenes solo si cada impuesto tiene datos
                                          $rentaImg = '<img src="./images/icon/dian/f-110.png" width="55%" alt="formato DIAN"> <br>';
                                          $ivaImg = '<img src="./images/icon/dian/f-300.png" width="55%" alt="formato DIAN"> <br>';
                                          $fuenteImg = '<img src="./images/icon/dian/f-350.png" width="55%" alt="formato DIAN"> <br>';

                                          // Solo mostrar la fila si al menos uno de los campos de impuestos tiene un valor no vacío
                                          if (!empty($renta) || !empty($iva_fecha) || !empty($retefuente)):
                                          ?>
                                              <tr>
                                                  <td class='text-center'>
                                                      <img class='rounded img-fluid avatar-40' 
                                                           src='<?php echo $row['file']; ?>' alt='profile'>
                                                      <br> <?php echo $row['empresa']; ?>
                                                  </td>
                                                  <?php 
                                                  // Mostrar las celdas de impuestos solo si hay datos
                                                  if ($mostrar_impuestos) {
                                                  ?>

                                                <?php 

                                                $sql = "SELECT SUM(`invoice_lines`.`tax_amount`) AS total_tax_amount 
                                      FROM `invoices` 
                                      LEFT JOIN `invoice_lines` ON `invoices`.`id` = `invoice_lines`.`invoice_id`
                                      WHERE `client_id` = ? 
                                      AND `issue_date` BETWEEN ? AND ? AND  `invoice_lines`.`tax_percent` NOT IN ('8.00');";

                              $stmt = $conn->prepare($sql);

                              // Definimos los parámetros para la consulta
                              $client_id = 7;
                              $start_date = '2024-09-01';
                              $end_date = '2024-10-31';

                              // Asignamos los valores con bind_param
                              $stmt->bind_param('iss', $client_id, $start_date, $end_date);

                              // Ejecutamos la consulta
                              $stmt->execute();
                              $result = $stmt->get_result();

                              // Obtenemos el resultado en una variable
                              $total_amount = 0;
                              if ($row = $result->fetch_assoc()) {
                                  $total_tax_amount = $row['total_tax_amount'];
                              }



                                                ?>

                                                      <td class='text-center'><?php echo $rentaImg. ($renta ?: 'Sin Definir'); ?> <br> <strong>$<?php echo number_format(000, 2, '.', ','); ?></strong> <i class="ri-send-plane-2-line"></i></td>
                                                      <td class='text-center'><?php echo $ivaImg. ($iva_fecha ?: 'Sin Definir'); ?> <br> <strong>$<?php echo number_format(000, 2, '.', ','); ?></strong> <i class="ri-send-plane-2-line"></i></td>
                                                      <td class='text-center'><?php echo $fuenteImg . ($retefuente ?: 'Sin Definir'); ?> <br> <strong>$<?php echo number_format(000, 2, '.', ','); ?></strong> <i class="ri-send-plane-2-line"></i></td>
                                                  <?php 
                                                  } 
                                                  ?>
                                              </tr>
                                          <?php endif; ?>
                                      <?php endwhile; ?>
                                  <?php else: ?>
                                      <tr><td colspan="9" class="text-center">No se encontraron registros...</td></tr>
                                  <?php endif; ?>
                               </tbody>
                             </table>
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
      <!-- Raphael-min JavaScript -->
      <script src="js/raphael-min.js"></script>
      <!-- Morris JavaScript -->
      <script src="js/morris.js"></script>
      <!-- Morris min JavaScript -->
      <script src="js/morris.min.js"></script>
      <!-- Flatpicker Js -->
      <script src="js/flatpickr.js"></script>
      <!-- Style Customizer -->
      <script src="js/style-customizer.js"></script>
      <!-- Chart Custom JavaScript -->
      <script src="js/chart-custom.js"></script>
      <!-- Custom JavaScript -->
      <script src="js/custom.js"></script>
   </body>
</html>