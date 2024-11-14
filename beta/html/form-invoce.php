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

   <!-- Wrapper Start -->
   <div class="wrapper">
      <!-- Sidebar and Header -->
      <?php include './nav.php'; ?>
      <?php include './header.php'; ?>

      <!-- Page Content -->
      <div id="content-page" class="content-page">
         <div class="container-fluid invoice-section">
            <div class="row">
               <div class="col-lg-12">

                  <!-- Header with Logo and Information Preview -->
                  <div class="invoice-header">
                     <div class="logo-container">
                        <img src="images/logo.png" alt="Logo de la Empresa">
                     </div>
                     <div class="info-preview">
                        <h4 class="card-title">Crear Factura</h4>
                        <p>Número de Factura: 00123</p>
                        <p>Fecha: <span id="currentDate"></span></p>
                     </div>
                  </div>

                  <div class="iq-card">
                     <div class="iq-card-body">
                        <form action="your_backend_script.php" method="POST">
                           <!-- Cliente -->
                           <div class="form-group">
                              <label for="customer">Cliente:</label>
                              <select id="customer" name="customer" required>
                                 <option value="123456789">Alan Turing</option>
                                 <option value="987654321">Ada Lovelace</option>
                              </select>
                           </div>

                           <!-- Rango de Numeración -->
                           <div class="form-group">
                              <label for="numbering_range_id">ID de Rango de Numeración:</label>
                              <input type="number" id="numbering_range_id" name="numbering_range_id" required>
                           </div>

                           <!-- Código de Referencia -->
                           <div class="form-group">
                              <label for="reference_code">Código de Referencia:</label>
                              <input type="text" id="reference_code" name="reference_code" required>
                           </div>

                           <!-- Observación -->
                           <div class="form-group">
                              <label for="observation">Observación:</label>
                              <textarea id="observation" name="observation"></textarea>
                           </div>

                           <!-- Método de Pago -->
                           <div class="form-group">
                              <label for="payment_method_code">Método de Pago:</label>
                              <input type="text" id="payment_method_code" name="payment_method_code" required>
                           </div>

                           <!-- Productos -->
                           <div id="product-container">
                              <h5>Productos</h5>
                              <div class="product-item">
                                 <div class="form-group">
                                    <label for="product_code_reference">Código de Producto:</label>
                                    <input type="text" name="product_code_reference[]" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="product_name">Nombre del Producto:</label>
                                    <input type="text" name="product_name[]" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="product_quantity">Cantidad:</label>
                                    <input type="number" name="product_quantity[]" min="1" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="product_price">Precio:</label>
                                    <input type="number" name="product_price[]" min="0" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="product_discount">Descuento:</label>
                                    <input type="number" name="product_discount[]" min="0">
                                 </div>
                                 <div class="form-group">
                                    <label for="product_tax_rate">Tasa de Impuesto (%):</label>
                                    <input type="number" name="product_tax_rate[]" step="0.01">
                                 </div>
                              </div>
                           </div>

                           <button type="button" onclick="addProduct()">Agregar Producto</button>
                           <button type="submit">Crear Factura</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Footer -->
   <?php include './footer.php'; ?>

   <!-- Scripts -->
   <script src="js/jquery.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script>
      // Set current date
      document.getElementById("currentDate").innerText = new Date().toLocaleDateString();

      function addProduct() {
         // Function to dynamically add new product item fields
      }
   </script>
</body>
</html>
