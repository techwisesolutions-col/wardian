<?php
require '../../config/conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener las contraseñas del formulario
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $token = $_POST['token'] ?? '';

    // Validar campos obligatorios
    if (empty($password) || empty($confirm_password)) {
        die("Ambos campos de contraseña son obligatorios.");
    }

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        die("Las contraseñas no coinciden.");
    }

    // Validar fortaleza de la contraseña (opcional)
    if (strlen($password) < 8) {
        die("La contraseña debe tener al menos 8 caracteres.");
    }

    if (empty($token)) {
        die("Token no válido.");
    }

    // Verificar si el token es válido y obtener el usuario asociado
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("El enlace para restablecer la contraseña ha caducado o no es válido.");
    }

    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    // Actualizar la contraseña del usuario
    $update_stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $password, $user_id);

    if ($update_stmt->execute()) {
        // Eliminar el token de restablecimiento para evitar reutilización
        $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();

        // Redirigir en caso de éxito
        header("Location: ../../html/sign-in.php?success=2");
        exit();

    } else {
        die("Ocurrió un error al restablecer la contraseña. Intenta nuevamente.");
    }
}
?>