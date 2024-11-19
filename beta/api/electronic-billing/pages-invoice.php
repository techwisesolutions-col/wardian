<?php
session_start();
require '../../config/conexion.php';

header('Content-Type: application/json');

try {
    // Validación de sesión de usuario
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception("Usuario no autenticado.");
    }

    // Recibir y validar datos del formulario
    $numbering_range_id = $_POST['numbering_range_id'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $payment_method_code = $_POST['payment_method_code'] ?? null;
    $id_third = $_POST['id_third'] ?? null;
    $observation = $_POST['observation'] ?? null;
    $products = is_array($_POST['product']) ? $_POST['product'] : [$_POST['product']];

    if (!$numbering_range_id || !$fecha || !$payment_method_code || !$id_third || empty($products)) {
        throw new Exception("Datos incompletos o inválidos.");
    }

    // Preparar productos
    $productos = [];
    foreach ($products as $index => $product) {
        $productos[] = [
            'product' => $product,
            'quantity' => $_POST['quantity'][$index],
            'unit_value' => $_POST['unit_value'][$index],
            'discount' => $_POST['discount'][$index],
            'charge_tax' => $_POST['charge_tax'][$index],
            'withholding_tax' => $_POST['withholding_tax'][$index],
            'value_total' => $_POST['value_total'][$index]
        ];
    }

    // Insertar factura en la base de datos
    $sql_invoice = "INSERT INTO invoices_electronic 
                    (generating_company, numbering_range_id, invoice_date, payment_method_code, id_third, observation) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_invoice = $conn->prepare($sql_invoice);
    $stmt_invoice->bind_param('ssssis', $user_id, $numbering_range_id, $fecha, $payment_method_code, $id_third, $observation);

    if (!$stmt_invoice->execute()) {
        throw new Exception("Error al insertar factura: " . $stmt_invoice->error);
    }

    $invoice_id = $stmt_invoice->insert_id;

    // Insertar productos asociados
    $sql_product = "INSERT INTO invoice_products_electronic 
                    (invoice_id, product_id, quantity, unit_value, discount, charge_tax, withholding_tax, value_total) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_product = $conn->prepare($sql_product);

    foreach ($productos as $producto) {
        $stmt_product->bind_param(
            'iiiddddd',
            $invoice_id,
            $producto['product'],
            $producto['quantity'],
            $producto['unit_value'],
            $producto['discount'],
            $producto['charge_tax'],
            $producto['withholding_tax'],
            $producto['value_total']
        );

        if (!$stmt_product->execute()) {
            throw new Exception("Error al insertar producto: " . $stmt_product->error);
        }
    }

    // Preparar JSON para la API
    $items = array_map(function ($producto) {
        return [
            'code_reference' => $producto['product'],
            'name' => 'prueba',
            'quantity' => $producto['quantity'],
            'discount' => 0,
            'discount_rate' => $producto['discount'],
            'price' => $producto['unit_value'],
            'tax_rate' => $producto['charge_tax'],
            'unit_measure_id' => 70,
            'standard_code_id' => 1,
            'is_excluded' => 0,
            'tribute_id' => 1,
            'withholding_taxes' => [
                ['code' => "06", 'withholding_tax_rate' => "7.00"],
                ['code' => "05", 'withholding_tax_rate' => "15.00"]
            ]
        ];
    }, $productos);

    $api_payload = [
        'numbering_range_id' => 38,
        'reference_code' => 'I3',
        'observation' => $observation,
        'payment_method_code' => $payment_method_code,
        'customer' => [
            'identification' => '123456789',
            'dv' => '3',
            'company' => '',
            'trade_name' => '',
            'names' => 'Alan Turing',
            'address' => 'calle 1 # 2-68',
            'email' => 'alanturing@enigmasas.com',
            'phone' => '1234567890',
            'legal_organization_id' => '2',
            'tribute_id' => '21',
            'identification_document_id' => '3',
            'municipality_id' => '980'
        ],
        'items' => $items
    ];

    // Enviar solicitud a la API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api-sandbox.factus.com.co/v1/bills/validate',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($api_payload),
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZDYwYTVjNy04Y2Q5LTQzNDMtOTdjMy0xNWU2YzRkNGQ4ZjMiLCJqdGkiOiJhOGI2MTJhMmVlMzJlOTNiM2M4MzllOThkMjg4NDU0MzM5ODBiNDg0OGE0MDUzZWExYzAzZGNhYTA1NTUyODU3M2U2MmYzZjI0ZjVlM2NlNyIsImlhdCI6MTczMTcwMDk4MC4zMDg1NTksIm5iZiI6MTczMTcwMDk4MC4zMDg1NjMsImV4cCI6MTczMTcwNDU4MC4yOTQzNzQsInN1YiI6IjMiLCJzY29wZXMiOltdfQ.fhbqrXaaiX-jPXthvc4EatsP6YDe61c1fTaEGy9Ry90N-ZF-XCPpuA07mK6Ky3bImr-AcgwyrlLE_l3w0Xle8CZ3DKR_ls3ztz4QkJ88_HrmcmsWJWeok1DJjfWPLnybQ_D2Ggv078sPOI4Q2e44pt2CD86e5XBbR1mHRjvP0znUvG_qwuYnTv8hWab_Vc1v1GcfPJBuD9z60vqi-ZdGIx9t3lUQIvoAhF33899KC2A1NYtajxfkEtRjdJhAxVH6ZBgYe3Y46mHt3mYkOglzB9BWiA47I-DW07jwGxM16aaEKT4jVfVAIWS2nOhQaygSsQbxaPWJBzW6fROojrQX-gAbwoDU0JdIXvB2UMUDpgP8CsNfjJoS3EeKfmErEQIajL7rzeF7nhGZ5bWGf8O-EroIYYJTd1q-seo_51_OfSGCl5r1pDdgiqlc-qFmYu3X8gKNE24bl3MNXqxo9qgc1tGYVOrAC4ZK6K0JVO3ejerscoCX39qYvWK1E-w65L2-d4oWvgfONIkh4Y78LqDLZScfREwJd3iLlFj7YKznmxvv-5FDaLi4DuSINJkkQpWiPPR2bfZpvgXgo7NF3rlCjdEBLGmhuBb-Lwy0Ikj0HalsAqOEqmQyt7EQHDCdyo9NcjBU3r_H-f-tQ6OSQ--2IvzyEnuBEu1_U6-nd4_l0l0'
        ],
    ]);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        throw new Exception("Error en cURL: " . curl_error($curl));
    }
    curl_close($curl);

    echo "Respuesta de la API:<br>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
} finally {
    // Liberar recursos
    if (isset($stmt_invoice)) $stmt_invoice->close();
    if (isset($stmt_product)) $stmt_product->close();
    $conn->close();
}
?>
