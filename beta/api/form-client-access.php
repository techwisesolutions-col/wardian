<?php
session_start();
require '../config/conexion.php'; // Conexión a la base de datos

try {
    // Verificar que todos los campos fueron enviados
    $required_fields = ['cliente_id', 'aplicativo', 'usuario', 'clave', 'url'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    // Si faltan campos, redirigir con error
    if (!empty($missing_fields)) {
        $fields = implode(', ', $missing_fields);
        $cliente_id = $_POST['cliente_id'];
        header("Location: ../html/form-client-access.php?error=missing_fields&fields=$fields&cliente_id=$cliente_id");
        exit();
    }

    // Preparar la consulta para insertar el acceso
    $stmt = $conn->prepare(
        "INSERT INTO accesos (cliente_id, aplicativo, usuario, clave, url) 
         VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param(
        "issss",
        $_POST['cliente_id'],
        $_POST['aplicativo'],
        $_POST['usuario'],
        $_POST['clave'],
        $_POST['url']
    );

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Redirigir en caso de éxito
    $cliente_id = $_POST['cliente_id'];
    header("Location: ../html/form-client-access.php?success=1&cliente_id=$cliente_id");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage(), 3, "../logs/error.log");
    die("Ocurrió un error. Por favor, revise los logs.");
} finally {
    // Cerrar la conexión
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>