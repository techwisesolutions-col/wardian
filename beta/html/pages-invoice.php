<!doctype html>
<?php
require '../config/conexion.php'; // Conexión a la base de datos

// Obtener el parámetro id_third de la URL
$id_third = isset($_GET['id_third']) ? $_GET['id_third'] : null;

if ($id_third) {
    // Consulta SQL para obtener el registro específico
    $sql = "SELECT `id`, `name_or_business_name`, `type`, `document_type`, `nit`, `dv`, `address`, `city`, `state_or_region`, `postal_code`, `country`, `email`, `phone`, `tax_regime`, `payment_method`, `credit_days`, `registration_date` 
            FROM `invoice_recipients` 
            WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_third);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Pasar cada columna a una variable
        $id = $data['id'];
        $name_or_business_name = $data['name_or_business_name'];
        $type = $data['type'];
        $document_type = $data['document_type'];
        $nit = $data['nit'];
        $dv = $data['dv'];
        $address = $data['address'];
        $city = $data['city'];
        $state_or_region = $data['state_or_region'];
        $postal_code = $data['postal_code'];
        $country = $data['country'];
        $email = $data['email'];
        $phone = $data['phone'];
        $tax_regime = $data['tax_regime'];
        $payment_method = $data['payment_method'];
        $credit_days = $data['credit_days'];
        $registration_date = $data['registration_date'];

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
                  <div class="col-sm-12">
                     <div class="iq-card">
                        <div class="iq-card-body">
                           <div class="row">
                              <div class="col-lg-6">
                                 <img src="images/logo.png" class="img-fluid avatar-100" alt="">
                              </div>
                              <div class="col-lg-6 align-self-center">
                                 <h4 class="mb-0 float-right">Factura #</h4>
                              </div>
                              <div class="col-sm-12">
                                 <hr class="mt-3">
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="table-responsive-sm">
                                    <table class="table">
                                       <thead>
                                          <tr>
                                             <th scope="col">Fecha</th>
                                             <th scope="col">Estado Actual</th>
                                             <th scope="col">Remitente</th>
                                             <th scope="col">Remisor</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr>
                                             <td>Nov 12, 2024</td>
                                             <td><span class="badge badge-danger">No pagada</span></td>
                                             <td>
                                                <p class="mb-0">TechWise Solutions SAS<br>
                                                   Celular: <br>
                                                   Correo Electronico: <br>
                                                   Dirección: <br>
                                                </p>
                                             </td>
                                             <td>
                                                <p class="mb-0"><?php echo $name_or_business_name; ?><br>
                                                   Celular: <?php echo $phone; ?><br>
                                                   Correo Electronico: <?php echo $email; ?><br>
                                                   Dirección: <?php echo $address.', '.$city.', '.$state_or_region; ?><br>
                                                </p>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-sm-12">
                                 <h5>Order Summary</h5>
                                 <div class="table-responsive-sm">
                                   <table class="table table-striped" id="products-table">
                                      <thead>
                                        <tr>
                                          <th class="text-center" scope="col">#</th>
                                          <th scope="col">Producto</th>
                                          <th class="text-center" scope="col">Cantidad</th>
                                          <th class="text-center" scope="col">Valor Unitario</th>
                                          <th class="text-center" scope="col">% Descuento</th>
                                          <th class="text-center" scope="col">Impuesto de Cargo</th>   
                                          <th class="text-center" scope="col">Impuesto de Retencion</th>
                                          <th class="text-center" scope="col">Valor Total</th>
                                          <th class="text-center" scope="col">Acciones</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th class="text-center" scope="row">1</th>
                                          <td>
                                            <select class="form-control product-select" name="product" required>
                                              <option value="" disabled selected>Seleccione un producto</option>
                                              <?php
                                              require '../config/conexion.php';
                                              $query = "SELECT id, product_name, price_list FROM products";
                                              $result = $conn->query($query);
                                              while ($row = $result->fetch_assoc()):
                                              ?>
                                                <option value="<?php echo htmlspecialchars($row['id']); ?>" data-price="<?php echo htmlspecialchars($row['price_list']); ?>">
                                                  <?php echo htmlspecialchars($row['product_name']); ?>
                                                </option>
                                              <?php endwhile; ?>
                                            </select>
                                          </td>
                                          <td class="text-center"><input type="number" class="form-control quantity" name="quantity" value="1" required></td>
                                          <td class="text-center"><input type="number" class="form-control unit-value" name="unit_value" required readonly></td>
                                          <td class="text-center"><input type="number" class="form-control discount" name="discount" required></td>
                                          <td class="text-center"><input type="number" class="form-control charge_tax" name="charge_tax" required></td>
                                          <td class="text-center"><input type="number" class="form-control withholding-tax" name="withholding_tax" required></td>
                                          <td class="text-center"><input type="number" class="form-control total-value" name="value_total" readonly></td>
                                          <td class="text-center">
                                            <button type="button" class="btn btn-primary add-row">+</button>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                 </div>
                              </div>
                              <div class="col-sm-6"></div>
                                <div class="text-right">
                                  <label for="grand-total">Total General: </label>
                                  <input type="number" id="grand-total" class="form-control" readonly>
                                </div>
                              <div class="col-sm-6 text-right">
                                 <button type="button" class="btn btn-primary"><i class="ri-printer-line"></i> Crear Factura y Enviar</button>
                              </div>
                              <div class="col-sm-12 mt-5">
                                 <b class="text-danger">Nota:</b>
                                 <p><textarea type="text" class="form-control" name="note" value="1" rows="2" cols="25"></textarea></p>
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
document.addEventListener('DOMContentLoaded', () => {
  const table = document.getElementById('products-table').getElementsByTagName('tbody')[0];
  const grandTotalField = document.getElementById('grand-total');

  // Function to calculate total value for each row
  function calculateTotal(row) {
    const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
    const unitValue = parseFloat(row.querySelector('.unit-value').value) || 0;
    const discount = parseFloat(row.querySelector('.discount').value) || 0;
    const withholdingTax = parseFloat(row.querySelector('.withholding-tax').value) || 0;
    const chargeTax = parseFloat(row.querySelector('.charge_tax').value) || 0;

    let total = quantity * unitValue;
    total -= total * (discount / 100);
    total -= total * (withholdingTax / 100);
    let totalChargeTax = total * (chargeTax / 100);
    total += totalChargeTax;

    row.querySelector('.total-value').value = total.toFixed(2);
    calculateGrandTotal();
  }

  // Function to calculate the grand total of all rows
  function calculateGrandTotal() {
    let grandTotal = 0;
    table.querySelectorAll('.total-value').forEach(input => {
      grandTotal += parseFloat(input.value) || 0;
    });
    grandTotalField.value = grandTotal.toFixed(2);
  }

  // Event listener to add a new row
  document.querySelector('.add-row').addEventListener('click', () => {
    const newRow = table.rows[0].cloneNode(true);
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    table.appendChild(newRow);
  });

  // Event delegation for dynamically added rows
  table.addEventListener('change', (event) => {
    const target = event.target;
    if (target.classList.contains('product-select')) {
      const unitValueInput = target.closest('tr').querySelector('.unit-value');
      const selectedOption = target.options[target.selectedIndex];
      const price = selectedOption.getAttribute('data-price');
      unitValueInput.value = price || 0;
      calculateTotal(target.closest('tr'));
    }
  });

  // Event listener to update total when inputs change
  table.addEventListener('input', (event) => {
    if (['quantity', 'unit-value', 'discount', 'withholding-tax', 'charge_tax'].some(cls => event.target.classList.contains(cls))) {
      calculateTotal(event.target.closest('tr'));
    }
  });

  // Event listener to remove rows
  table.addEventListener('click', (event) => {
    if (event.target.classList.contains('remove-row') && table.rows.length > 1) {
      event.target.closest('tr').remove();
      calculateGrandTotal(); // Recalculate grand total after removing a row
    }
  });
});

</script>
   </body>
</html>
