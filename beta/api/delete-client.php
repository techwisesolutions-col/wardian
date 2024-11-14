<?php
require '../config/conexion.php'; // Conexión a la base de datos

if (isset($_GET['cliente_id'])) {
    $cliente_id = $_GET['cliente_id'];

    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $cliente_id);

    if ($stmt->execute()) {
        header("Location: ../client-list.php?success=1");
        exit();
    } else {
        header("Location: ../client-list.php?error=delete_failed");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../client-list.php?error=missing_id");
    exit();
}
?>