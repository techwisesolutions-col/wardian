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

                        <?php if (isset($_GET['error']) && $_GET['error'] == 'resolution_exists'): ?>
                           <div class="alert alert-danger" role="alert">
                              <div class="iq-alert-icon">
                                    <i class="ri-alert-line"></i>
                              </div>
                              <div class="iq-alert-text">
                                    <strong>Error:</strong> La resolución ya existe en el sistema.
                              </div>
                           </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                           <div class="alert alert-success" role="alert">
                              <div class="iq-alert-icon">
                                    <i class="ri-check-line"></i>
                              </div>
                              <div class="iq-alert-text">
                                    <strong>¡Éxito!</strong> Rango de numeración registrado con éxito.
                              </div>
                           </div>
                        <?php endif; ?>
                       
                       <?php if (isset($_GET['success']) && $_GET['success'] == 2): ?>
                           <div class="alert alert-success" role="alert">
                              <div class="iq-alert-icon">
                                    <i class="ri-check-line"></i>
                              </div>
                              <div class="iq-alert-text">
                                    <strong>¡Éxito!</strong> Rangos de numeración obtenidos con éxito.
                              </div>
                           </div>
                        <?php endif; ?>

                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                                 <div class="iq-header-title">
                                    <h4 class="card-title">Rangos De Numeración</h4>
                                 </div>
                           </div>
                           <div class="iq-card-body">
                                 <form method="POST" action="../api/numbering-ranges.php" novalidate>
                                    <div class="form-row">
                                       <div class="col-md-6 mb-3">
                                              <label for="tipo_tercero">Documento</label>
                                              <select class="form-control" id="document" name="document" required>
                                                  <option value="" disabled selected>Seleccione...</option>
                                                  <?php
                                                  // Consulta para obtener documentos
                                                  $query = "SELECT `document_id`, `name` FROM `documents`";
                                                  $result = $conn->query($query);

                                                  // Genera las opciones del select
                                                  if ($result->num_rows > 0) {
                                                      while ($row = $result->fetch_assoc()) {
                                                          echo "<option value='{$row['document_id']}'>{$row['document_id']} - {$row['name']}</option>";
                                                      }
                                                  } else {
                                                      echo "<option value='' disabled>No hay documentos disponibles</option>";
                                                  }

                                                  // Cierra la conexión
                                                  $conn->close();
                                                  ?>
                                              </select>
                                          </div>

                                       <div class="col-md-6 mb-3">
                                             <label for="empresa">Prefijo</label>
                                             <input type="text" class="form-control" id="prefix" name="prefix" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Número de inicio del rango</label>
                                             <input type="number" class="form-control" id="from" name="from" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Número final del rango</label>
                                             <input type="number" class="form-control" id="to" name="to" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Número actual del consecutivo. <a data-toggle='tooltip' 
                                                               data-placement='top' title='NOTA  
Si el consecutivo es nuevo debe agregar el mismo numero del campo NUMERO DE INICIO DEL RANGO.Si el consecutivo se ha usado, debe agregar el numero del último consecutivo usado.' href='#'>
                                                                  <i class="ri-alert-line"></i> Importante
                                                            </a></label>
                                             <input type="number" class="form-control" id="current" name="current" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Número de resolución del rango.  <a data-toggle='tooltip' 
                                                               data-placement='top' title='NOTA Solo es opcional si el campo documento contiene el codigo 21 - Factura de Venta o 24 - Documento Soporte.' href='#'>
                                                                  <i class="ri-alert-line"></i> Importante
                                               </a></label>
                                             <input type="text" class="form-control" id="resolution_number" name="resolution_number" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Fecha de expedición del rango de numeración.</label>
                                             <input type="date" class="form-control" id="start_date" name="start_date" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Fecha de expiración del rango de numeración.</label>
                                             <input type="date" class="form-control" id="end_date" name="end_date" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      <div class="col-md-6 mb-3">
                                             <label for="empresa">Clave Tecnica.</label>
                                             <input type="text" class="form-control" id="technical_key" name="technical_key" oninput="convertirAMayusculas(this)" required>
                                       </div>
                                      
                                      
                                    </div>
                                    <a class="btn btn-primary" href="../api/electronic-billing/get-all-numbering-ranges.php">Obtener Rangos</a>
                                    <button class="btn btn-primary" type="submit">Crear Rango</button>
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
                    <h4 class="card-title">Mis Rangos de Numeración</h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="table-responsive">
                    <div class="row justify-content-between">
                        <div class="col-sm-12 col-md-6">
                            <div id="user_list_datatable_info" class="dataTables_filter">
                                <form method="GET" class="mr-3 position-relative">
                                    <div class="form-group mb-0">
                                        <input type="search" class="form-control" name="search" id="exampleInputSearch" placeholder="Buscar..." aria-controls="user-list-table" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                    </div>
                                </form>
                            </div>
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
                    $search_query = $search ? "WHERE documents.name LIKE '%$search%' OR numbering_ranges.prefix LIKE '%$search%' OR numbering_ranges.resolution_number LIKE '%$search%'" : '';

                    // Contar total de registros
                    $count_sql = "SELECT COUNT(*) as total FROM numbering_ranges LEFT JOIN documents ON numbering_ranges.document = documents.document_id $search_query";
                    $count_result = $conn->query($count_sql);
                    $total_records = $count_result->fetch_assoc()['total'];
                    $total_pages = ceil($total_records / $limit); // Número total de páginas

                    // Consulta con paginación y búsqueda
                    $sql = "SELECT * FROM numbering_ranges LEFT JOIN documents ON numbering_ranges.document = documents.document_id $search_query LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);
                    ?>

                    <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                        <thead>
                            <tr>
                                <th class='text-center'>Documento</th>
                                <th class='text-center'>Prefijo</th>
                                <th class='text-center'>Número de inicio</th>
                                <th class='text-center'>Número final</th>
                                <th class='text-center'>Número de resolución</th>
                                <th class='text-center'>Fecha de expedición</th>
                                <th class='text-center'>Fecha de expiración</th>
                                <th class='text-center'>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['name']}</td>
                                        <td>{$row['prefix']}</td>
                                        <td>{$row['from']}</td>
                                        <td>{$row['to']}</td>
                                        <td>{$row['resolution_number']}</td>
                                        <td>{$row['start_date']}</td>
                                        <td>{$row['end_date']}</td>
                                        <td>
                                            <div class='flex align-items-center list-user-action'>
                                                <a class='iq-bg-primary' data-toggle='tooltip' data-placement='top' title='Eliminar' href=''><i class='ri-delete-bin-line'></i></a>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No se encontraron registros...</td></tr>";
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