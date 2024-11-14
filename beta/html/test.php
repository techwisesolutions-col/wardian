<!doctype html>
<?php
require '../config/conexion.php'; // Conexión a la base de datos

// Definir la cantidad de registros por página
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$busqueda = isset($_GET['search']) ? $_GET['search'] : '';

// Calcular el offset para la consulta SQL
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta SQL con JOIN y paginación
$sql = "SELECT 
            c.empresa, 
            c.nit, 
            RIGHT(c.nit, 1) AS ultimo_digito, 
            ct.tipo_impuesto, 
            ct.fecha_limite 
        FROM 
            clientes c
        LEFT JOIN 
            calendario_tributario ct ON RIGHT(c.nit, 1) = ct.digito
        WHERE 
            c.empresa LIKE ? 
        LIMIT ?, ?";
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
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>WARDIAN - SYSTEM</title>
      <link rel="shortcut icon" href="images/favicon.ico" />
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/typography.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/responsive.css">
   </head>
   <body>
      <div id="loading">
         <div id="loading-center"></div>
      </div>
      <div class="wrapper">
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
         <?php include './header.php'; ?>
         <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <div class="iq-header-title">
                              <h4 class="card-title">Lista De Clientes</h4>
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
                                       <th>Empresa</th>
                                       <th>NIT</th>
                                       <th>Último Dígito</th>
                                       <th>Tipo Impuesto</th>
                                       <th>Fecha Límite</th>
                                       <th>Acciones</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                       <?php while ($row = $result->fetch_assoc()): ?>
                                          <tr>
                                             <td><?php echo $row['empresa']; ?></td>
                                             <td><?php echo $row['nit']; ?></td>
                                             <td><?php echo $row['ultimo_digito']; ?></td>
                                             <td><?php echo $row['tipo_impuesto']; ?></td>
                                             <td><?php echo $row['fecha_limite']; ?></td>
                                             <td>
                                                <div class='flex align-items-center list-user-action'>
                                                   <a class='iq-bg-primary' data-toggle='tooltip' 
                                                      data-placement='top' title='Accesos' 
                                                      href='./form-client-access.php?cliente_id=<?php echo $row['id']; ?>'>
                                                      <i class='ri-user-add-line'></i>
                                                   </a>
                                                   <a class='iq-bg-primary' data-toggle='tooltip' 
                                                      data-placement='top' title='Editar' href='#'>
                                                      <i class='ri-pencil-line'></i>
                                                   </a>
                                                   <a class='iq-bg-primary' data-toggle='tooltip' 
                                                      data-placement='top' title='Eliminar' href='../api/delete-client.php?cliente_id=<?php echo $row['id']; ?>'>
                                                      <i class='ri-delete-bin-line'></i>
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       <?php endwhile; ?>
                                    <?php else: ?>
                                       <tr><td colspan="6" class="text-center">No se encontraron registros...</td></tr>
                                    <?php endif; ?>
                                 </tbody>
                              </table>

                              <nav aria-label="Page navigation example">
                                 <ul class="pagination justify-content-end mb-0">
                                    <li class="page-item <?php if ($pagina_actual <= 1) echo 'disabled'; ?>">
                                       <a class="page-link" href="?page=<?php echo $pagina_actual - 1; ?>&search=<?php echo $busqueda; ?>">Previous</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                       <li class="page-item <?php if ($i == $pagina_actual) echo 'active'; ?>">
                                          <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $busqueda; ?>"><?php echo $i; ?></a>
                                       </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?php if ($pagina_actual >= $total_paginas) echo 'disabled'; ?>">
                                       <a class="page-link" href="?page=<?php echo $pagina_actual + 1; ?>&search=<?php echo $busqueda; ?>">Next</a>
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
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/custom.js"></script>
   </body>
</html>
