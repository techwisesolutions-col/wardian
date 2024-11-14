<?php
session_start();
require '../config/conexion.php'; // Conexión a la base de datos

try {
    // Verificar que todos los campos fueron enviados
    $required_fields = [
        'name_or_business_name', 'type', 'document_type', 'nit', 'dv', 'address', 'city', 'state_or_region', 
        'country', 'email', 'tax_regime', 'payment_method'
    ];

    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    // Si faltan campos, redirigir con error
    if (!empty($missing_fields)) {
        $fields = implode(', ', $missing_fields);
        header("Location: ../html/form-client.php?error=missing_fields&fields=$fields");
        exit();
    }

    // Validar si el NIT ya existe
    $nit = $_POST['nit'];
    $document_type = $_POST['document_type'];
    $query = "SELECT id FROM invoice_recipients WHERE nit = ? AND document_type = ?";
    $checkStmt = $conn->prepare($query);
    if (!$checkStmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $checkStmt->bind_param("ss", $nit, $document_type);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        header("Location: ../html/form-client.php?error=id_exists");
        exit();
    }

    // Preparar la inserción del cliente
    $stmt = $conn->prepare(
        "INSERT INTO invoice_recipients (name_or_business_name, type, document_type, nit, dv, address, city, state_or_region, postal_code, country, email, phone, tax_regime, payment_method, credit_days, registration_date) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar la inserción: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssssss",
        $_POST['name_or_business_name'], $_POST['type'], $_POST['document_type'], $_POST['nit'], $_POST['dv'], $_POST['address'], 
        $_POST['city'], $_POST['state_or_region'], $_POST['postal_code'], $_POST['country'], 
        $_POST['email'], $_POST['phone'], $_POST['tax_regime'], $_POST['payment_method'], $_POST['credit_days']
    );

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la inserción: " . $stmt->error);
    }

    // Redirigir en caso de éxito
    header("Location: ../html/form-third.php?success=1");
    exit();

} catch (Exception $e) {
    // Registrar el error en los logs y mostrar mensaje genérico
    error_log($e->getMessage(), 3, "../logs/error.log");
    die("Ocurrió un error. Por favor, revise los logs.");
} finally {
    // Cerrar las conexiones
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($checkStmt)) {
        $checkStmt->close();
    }
    $conn->close();
}
?>
