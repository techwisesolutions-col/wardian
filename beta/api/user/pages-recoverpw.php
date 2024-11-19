<?php
require '../../config/conexion.php'; // Conexión a la base de datos

$mailtrap_url = 'https://send.api.mailtrap.io/api/send';
$mailtrap_token = '3d394b5db5ff660d4ee10b9ff471cc07';
$from_email = 'hello@demomailtrap.com';
$from_name = 'WARDIAN';  // Cambié el nombre del remitente aquí

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (empty($email)) {
        die("El campo de correo electrónico es obligatorio.");
    }

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

    $token = bin2hex(random_bytes(16));
    $reset_link = "https://www.techwisesolutions.com.co/wardian/beta/html/pages-recoverpw-reset.php?token=$token";

    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $token);
    $stmt->execute();

    // Diseño HTML para el correo
    $html_content = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7fa;
                padding: 20px;
            }
            .container {
                background-color: #e7ecee;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: 0 auto;
                font-size: 16px;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header img {
                width: 100px;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #170d82; /* Color de la plataforma */
                font-size: 24px;
            }
            .content {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 30px;
            }
            .content p {
                margin: 10px 0;
                font-size: 16px;
                color: #555;
            }
            .button {
                display: inline-block;
                padding: 12px 30px;
                background-color: #170d82; /* Color de la plataforma */
                color: #fff;
                font-size: 16px;
                border-radius: 5px;
                text-decoration: none;
                text-align: center;
                margin-top: 20px;
            }
            a.button {
                color: #fff !important;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #888;
                margin-top: 30px;
            }
            .social-icons {
                margin-top: 20px;
            }
            .social-icons a {
                margin: 0 10px;
                text-decoration: none;
            }
            .social-icons img {
                width: 20px;
                height: 20px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='https://www.techwisesolutions.com.co/wardian/beta/html/images/logo.png' alt='WARDIAN Logo'>
                <h1>Restablecimiento de Contraseña</h1>
            </div>
            <div class='content'>
               <center>
                <p>Hola <strong>$user_name</strong>,</p>
                <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para proceder:</p>
                <p><a href='$reset_link' class='button'>Restablecer mi contraseña</a></p>
               </center> 
            </div>
            <div class='footer'>
                <p>Si no solicitaste este cambio, ignora este mensaje.</p>
                <div class='social-icons'>
                    <a href='https://www.instagram.com/wardian.col' target='_blank'>
                        <img src='https://i.pinimg.com/originals/54/89/1e/54891eaea1b957218296e2714598de1a.png' alt='Instagram'>
                    </a>
                    <a href='https://twitter.com/WardianCol' target='_blank'>
                        <img src='https://www.utadeo.edu.co/sites/tadeo/files/node/wysiwyg/twitter-x-logo-0339f999cf-seeklogo.com_.png' alt='Twitter'>
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail_data = [
        "from" => ["email" => $from_email, "name" => $from_name],
        "to" => [["email" => $email]],
        "subject" => "Restablecimiento de contraseña",
        "html" => $html_content,
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
    if ($response === false) {
        die("cURL Error: " . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 || $http_code === 201) {

        // Redirigir en caso de éxito
        header("Location: ../../html/pages-recoverpw.php?success=1");
        exit();

    } else {
        echo "HTTP Code: $http_code<br>";
        echo "Response: $response<br>";
        die("Error al enviar el correo.");
    }
}
?>

