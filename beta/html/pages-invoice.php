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
                          <form action="../api/electronic-billing/pages-invoice.php" method="POST">
                           <div class="row">
                              <div class="col-lg-6">
                                 <img src="images/logo.png" class="img-fluid avatar-100" alt="">
                              </div>
                             
                              <div class="col-lg-6 align-self-center">
                                 <h4 class="mb-0 float-right">Factura Eletronica</h4>
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
                                                            <th scope="col">Tipo</th>
                                                            <th scope="col">Factura #</th>  
															<th scope="col">Fecha</th>
															<th scope="col">Remitente</th>
															<th scope="col">Remisor</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><select type="text" class="form-control" style="width: 120px;" name="numbering_range_id" id="numbering_range_id" required><option>Seleccione...</option><option>FV-2 Facturación Electronica</option></select><input type="hidden" name="id_third" id="id_third" value="4"/></td>
                                                            <td></td>
															<td><input type="date" class="form-control" style="width: 120px;" id="fecha" name="fecha" required/></td>
															<td>
																<p class="mb-0">TechWise Solutions SAS<br> Celular: <br> Correo Electronico: <br> Dirección: <br>
																</p>
															</td>
															<td>
																<p class="mb-0">
																	<?php echo $name_or_business_name; ?><br> Celular:
																	<?php echo $phone; ?><br> Correo Electronico:
																	<?php echo $email; ?><br> Dirección:
																	<?php echo $address.', '.$city.', '.$state_or_region; ?><br>
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
															<td class="text-center"><input type="number" class="form-control quantity" name="quantity[]" value="1" required></td>
															<td class="text-center"><input type="number" class="form-control unit-value" name="unit_value[]" required readonly></td>
															<td class="text-center"><input type="number" class="form-control discount" name="discount[]"></td>
															<td class="text-center"><input type="number" class="form-control charge_tax" name="charge_tax[]"></td>
															<td class="text-center"><input type="number" class="form-control withholding-tax" name="withholding_tax[]"></td>
															<td class="text-center"><input type="number" class="form-control total-value" name="value_total[]" readonly></td>
															<td class="text-center">
																<button type="button" class="btn btn-primary add-row">+</button>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-sm-6"></div>

										<div class="col-sm-6 text-right">

											<div class="text-right">
												<label for="subtotal">Subtotal: </label>
												<p id="subtotal" class="form-control-static">0.00</p>
											</div>
											<div class="text-right">
												<label for="total-discount">Total Descuento: </label>
												<p id="total-discount" class="form-control-static">0.00</p>
											</div>
											<div class="text-right">
												<label for="grand-total">Total General: </label>
												<p id="grand-total" class="form-control-static">0.00</p>
											</div>

                                       <br><select class="form-control" name="payment_method_code" id="payment_method_code" required>
                                                  <option disabled selected>Método de pago</option>
                                                  <option value="10">Efectivo</option>
                                                  <option value="42">Consignación</option>
                                                  <option value="20">Cheque</option>
                                                  <option value="46">Transferencia Débito Interbancario</option>
                                                  <option value="47">Transferencia</option>
                                                  <option value="71">Bonos</option>
                                                  <option value="72">Vales</option>
                                                  <option value="ZZZ">Otro*</option>
                                                  <option value="1">Medio de pago no definido</option>
                                                  <option value="49">Tarjeta Débito</option>
                                                  <option value="3">Débito ACH</option>
                                                  <option value="25">Cheque certificado</option>
                                                  <option value="26">Cheque Local</option>
                                                  <option value="24">Nota cambiaria esperando aceptación</option>
                                                  <option value="64">Nota promisoria firmada por el banco</option>
                                                  <option value="65">Nota promisoria firmada por un banco avalada por otro banco</option>
                                                  <option value="66">Nota promisoria firmada</option>
                                                  <option value="67">Nota promisoria firmada por un tercero avalada por un banco</option>
                                                  <option value="2">Crédito ACH</option>
                                                  <option value="95">Giro formato abierto</option>
                                                  <option value="13">Crédito Ahorro</option>
                                                  <option value="14">Débito Ahorro</option>
                                                  <option value="39">Crédito Intercambio Corporativo (CTX)</option>
                                                  <option value="4">Reversión débito de demanda ACH</option>
                                                  <option value="5">Reversión crédito de demanda ACH</option>
                                                  <option value="6">Crédito de demanda ACH</option>
                                                  <option value="7">Débito de demanda ACH</option>
                                                  <option value="9">Clearing Nacional o Regional</option>
                                                  <option value="11">Reversión Crédito Ahorro</option>
                                                  <option value="12">Reversión Débito Ahorro</option>
                                                  <option value="18">Desembolso (CCD) débito</option>
                                                  <option value="19">Crédito Pago negocio corporativo (CTP)</option>
                                                  <option value="21">Proyecto bancario</option>
                                                  <option value="22">Proyecto bancario certificado</option>
                                                  <option value="27">Débito Pago Negocio Corporativo (CTP)</option>
                                                  <option value="28">Crédito Negocio Intercambio Corporativo (CTX)</option>
                                                  <option value="29">Débito Negocio Intercambio Corporativo (CTX)</option>
                                                  <option value="30">Transferencia Crédito</option>
                                                  <option value="31">Transferencia Débito</option>
                                                  <option value="32">Desembolso Crédito plus (CCD+)</option>
                                                  <option value="33">Desembolso Débito plus (CCD+)</option>
                                                  <option value="34">Pago y depósito pre acordado (PPD)</option>
                                                  <option value="35">Desembolso Crédito (CCD)</option>
                                                  <option value="36">Desembolso Débito (CCD)</option>
                                                  <option value="48">Tarjeta Crédito</option>
                                                  <option value="44">Nota cambiaria</option>
                                                  <option value="23">Cheque bancario de gerencia</option>
                                                  <option value="61">Nota promisoria firmada por el acreedor</option>
                                                  <option value="62">Nota promisoria firmada por el acreedor, avalada por el banco</option>
                                                  <option value="63">Nota promisoria firmada por el acreedor, avalada por un tercero</option>
                                                  <option value="60">Nota promisoria</option>
                                                  <option value="96">Método de pago solicitado no usado</option>
                                                  <option value="91">Nota bancaria transferible</option>
                                                  <option value="92">Cheque local transferible</option>
                                                  <option value="93">Giro referenciado</option>
                                                  <option value="94">Giro urgente</option>
                                                  <option value="40">Débito Intercambio Corporativo (CTX)</option>
                                                  <option value="41">Desembolso Crédito plus (CCD+)</option>
                                                  <option value="43">Desembolso Débito plus (CCD+)</option>
                                                  <option value="45">Transferencia Crédito Bancario</option>
                                                  <option value="50">Postgiro</option>
                                                  <option value="51">Telex estándar bancario</option>
                                                  <option value="52">Pago comercial urgente</option>
                                                  <option value="53">Pago Tesorería Urgente</option>
                                                  <option value="15">Bookentry Crédito</option>
                                                  <option value="16">Bookentry Débito</option>
                                                  <option value="17">Desembolso Crédito (CCD)</option>
                                                  <option value="70">Retiro de nota por el acreedor</option>
                                                  <option value="74">Retiro de nota por el acreedor sobre un banco</option>
                                                  <option value="75">Retiro de nota por el acreedor, avalada por otro banco</option>
                                                  <option value="76">Retiro de nota por el acreedor, sobre un banco avalada por un tercero</option>
                                                  <option value="77">Retiro de una nota por el acreedor sobre un tercero</option>
                                                  <option value="78">Retiro de una nota por el acreedor sobre un tercero avalada por un banco</option>
                                                  <option value="37">Pago Negocio Corporativo Ahorros Crédito (CTP)</option>
                                                  <option value="38">Pago Negocio Corporativo Ahorros Débito (CTP)</option>
                                                  <option value="97">Clearing entre partners</option>
                                              </select>
                                          
                                          </div>
                                      
										<div class="col-sm-12 mt-5">
											<b class="text-danger">Nota:</b>
											<p><textarea type="text" class="form-control" name="observation" id="observation" value="1" rows="2" cols="25"></textarea></p>
										</div>
                                     
									</div>
                             <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary add-row"><i class="ri-printer-line"></i>Crear Factura y Enviar</button>
                                      </div> 
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
			  const totalDiscountField = document.getElementById('total-discount');
			  const subtotalField = document.getElementById('subtotal');
			
			  // Function to calculate total value for each row
			  function calculateTotal(row) {
			    const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
			    const unitValue = parseFloat(row.querySelector('.unit-value').value) || 0;
			    const discount = parseFloat(row.querySelector('.discount').value) || 0;
			    const withholdingTax = parseFloat(row.querySelector('.withholding-tax').value) || 0;
			    const chargeTax = parseFloat(row.querySelector('.charge_tax').value) || 0;
			
			    let subtotal = quantity * unitValue; // Calcula el subtotal sin aplicar descuentos
			    const discountAmount = subtotal * (discount / 100);
			    let total = subtotal - discountAmount;
			    total -= total * (withholdingTax / 100);
			    let totalChargeTax = total * (chargeTax / 100);
			    total += totalChargeTax;
			
			    row.querySelector('.total-value').value = total.toFixed(2);
			
			    calculateGrandTotal();
			    calculateTotalDiscount();
			    calculateSubtotal();
			  }
			
			  // Function to calculate the grand total of all rows
			  function calculateGrandTotal() {
			    let grandTotal = 0;
			    table.querySelectorAll('.total-value').forEach(input => {
			      grandTotal += parseFloat(input.value) || 0;
			    });
			    grandTotalField.textContent = grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			  }
			
			  // Function to calculate the total discount of all rows
			  function calculateTotalDiscount() {
			    let totalDiscount = 0;
			    table.querySelectorAll('tr').forEach(row => {
			      const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
			      const unitValue = parseFloat(row.querySelector('.unit-value').value) || 0;
			      const discount = parseFloat(row.querySelector('.discount').value) || 0;
			      const discountAmount = (quantity * unitValue) * (discount / 100);
			      totalDiscount += discountAmount;
			    });
			    totalDiscountField.textContent = totalDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			  }
			
			  // Function to calculate the subtotal of all rows (without discount)
			  function calculateSubtotal() {
			    let subtotal = 0;
			    table.querySelectorAll('tr').forEach(row => {
			      const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
			      const unitValue = parseFloat(row.querySelector('.unit-value').value) || 0;
			      subtotal += quantity * unitValue;
			    });
			    subtotalField.textContent = subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
			      calculateGrandTotal();
			      calculateTotalDiscount();
			      calculateSubtotal();
			    }
			  });
			});
			
			
			
		</script>

		<script>
			// Establece la fecha por defecto al día actual
			  document.getElementById("fecha").value = new Date().toISOString().split('T')[0];
			
		</script>
	</body>
	</html>