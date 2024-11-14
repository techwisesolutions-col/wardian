<?php
session_start();
require '../config/conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para obtener los datos del usuario
    $stmt = $conn->prepare("SELECT id, username, name, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verificar si el usuario existe y si la contraseña es correcta (sin hashing)
    if ($user['password'] == $password) { // Comparación directa (no segura)
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        header("Location: dashboard.php"); // Redirigir al panel de usuario
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>
