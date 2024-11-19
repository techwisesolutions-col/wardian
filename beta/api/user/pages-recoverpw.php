<?php
require '../../config/conexion.php'; // Conexión a la base de datos

// Configuración de Mailtrap
$mailtrap_url = 'https://send.api.mailtrap.io/api/send';
$mailtrap_token = '3d394b5db5ff660d4ee10b9ff471cc07';
$from_email = 'hello@demomailtrap.com';
$from_name = 'Mailtrap Test';

// Procesar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        die("El campo de correo electrónico es obligatorio.");
    }

    // Validar correo en la base de datos
    $stmt = $conn->prepare("SELECT id, name FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("El correo no está registrado.");
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $user_name = $user['name'];

    // Generar enlace de restablecimiento
    $token = bin2hex(random_bytes(16));
    $reset_link = "https://www.techwisesolutions.com.co/wardian/beta/html/?token=$token";

    // Guardar token en la base de datos (por simplicidad, asumimos que hay una tabla 'password_resets')
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();

    // Enviar el correo electrónico
    $mail_data = [
        "from" => ["email" => $from_email, "name" => $from_name],
        "to" => [["email" => $email]],
        "subject" => "Restablecimiento de contraseña",
        "text" => "Hola $user_name, usa este enlace para restablecer tu contraseña: $reset_link",
        "category" => "Password Reset"
    ];

    $ch = curl_init($mailtrap_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $mailtrap_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mail_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 || $http_code === 201) {
        echo "Se ha enviado un enlace de restablecimiento a tu correo.";
    } else {
        echo "Error al enviar el correo. Inténtalo de nuevo.";
    }
}
?>