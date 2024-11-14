<?php
session_start();
require '../config/conexion.php'; // Conexión a la base de datos

try {
    // Verificar que todos los campos fueron enviados
    $required_fields = [
        'empresa', 'nit', 'dv', 'representante_legal', 'tipo_documento_representante', 'cedula_representante_legal',
        'correo_electronico', 'telefono', 'direccion', 'ciudad', 'departamento',
        'actividad_comercial', 'clave_dian', 'firma_dian_aplicativo', 'ica', 'tipo_tercero'
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
    $query = "SELECT id FROM clientes WHERE nit = ?";
    $checkStmt = $conn->prepare($query);
    if (!$checkStmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $checkStmt->bind_param("s", $nit);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        header("Location: ../html/form-client.php?error=nit_exists");
        exit();
    }

    // Preparar la inserción del cliente
    $stmt = $conn->prepare(
        "INSERT INTO clientes (empresa, nit, dv, representante_legal, tipo_documento_representante, cedula_representante_legal, correo_electronico, telefono, direccion, ciudad, departamento, actividad_comercial, clave_dian, firma_dian_aplicativo, ica, tipo_tercero) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar la inserción: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssssssss",
        $_POST['empresa'], $_POST['nit'], $_POST['dv'], $_POST['representante_legal'], $_POST['tipo_documento_representante'],
        $_POST['cedula_representante_legal'], $_POST['correo_electronico'], $_POST['telefono'],
        $_POST['direccion'], $_POST['ciudad'], $_POST['departamento'], $_POST['actividad_comercial'],
        $_POST['clave_dian'], $_POST['firma_dian_aplicativo'], $_POST['ica'], $_POST['tipo_tercero']
    );

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la inserción: " . $stmt->error);
    }

    // Redirigir en caso de éxito
    header("Location: ../html/form-client.php?success=1");
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