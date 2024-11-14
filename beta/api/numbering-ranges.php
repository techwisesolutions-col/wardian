<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos requeridos y opcionales
    $required_fields = ['document', 'prefix', 'from', 'to', 'current'];
    $optional_fields = ['resolution_number', 'technical_key', 'start_date', 'end_date'];

    try {
        // Validar campos requeridos
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $missing_fields[] = $field;
            }
        }

        // Si faltan campos requeridos, redirigir con error
        if (!empty($missing_fields)) {
            $fields = implode(', ', $missing_fields);
            header("Location: ../html/numbering-ranges.php?error=missing_fields&fields=$fields");
            exit();
        }

        // Recibir todos los datos, incluyendo opcionales
        $data = [
            "document" => $_POST['document'],
            "prefix" => $_POST['prefix'],
            "from" => $_POST['from'],
            "to" => $_POST['to'],
            "current" => $_POST['current'],
            "resolution_number" => $_POST['resolution_number'] ?? null,
            "technical_key" => $_POST['technical_key'] ?? null,
            "start_date" => $_POST['start_date'] ?? null,
            "end_date" => $_POST['end_date'] ?? null
        ];

        // Convertir datos a JSON
        $json_data = json_encode($data);

        // Inicializar cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.factus.com.co/v1/numbering-ranges',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_data,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZDYwYTVjNy04Y2Q5LTQzNDMtOTdjMy0xNWU2YzRkNGQ4ZjMiLCJqdGkiOiI3NTg5MDYwN2MwZDQ1ZmEzYjZkZmQyODU3Njk3NDFiZDcxOTllZmQ2NWM0MjNhM2ZhMzc3YjFmMTlhMmY0Y2Y2N2UyMDI2MmEwM2U4MjNiMCIsImlhdCI6MTczMTA5Mjk2Ni40MjgzMiwibmJmIjoxNzMxMDkyOTY2LjQyODMyMywiZXhwIjoxNzMxMDk2NTY2LjQxMzkwOSwic3ViIjoiMyIsInNjb3BlcyI6W119.thbmiUGGCNDLqVKe2hb0Ue0ZMhe_R70yNGV5ZFccn_kNE_BZ8UwrvihAdb5-GL51owymcqLrR9gSn9SfD_6Lc0_h8laydfHQ7pDPQOiHBZBbX0ko3SWNeHMRXFRGVihz19cz2x4X5iapQup1Q018VaUZJ4Rc7gknTU2MrKPECGckJ6XWJxouNo8C6R8RE8lMg338K_KIMtvx2bZMrrChnEsu5uLS85Y7mtG6c3lFx70CzIX-Ixxkm_8pru69YaFd21_TQoQY9DOz5qHKza_RrBDnEPRVmLf-0d51HO5EOfjWMmi7nojJF56EEq72CzY52dV9E9KUBaq1nmytk1EHnfiCbbOAxtjWfOpEnJlZ6ThBXiuDJgtaOyRl8QMZkqHDT0C3JD9OTRIGNJN1HpzENTkAWk-KTTlyDTa3Bkd6bZBl8f9rZ_lcH-ucezBp_TiJnFfpJwxNKhiSScZoIjUuOfoacgV5-a_GffSCw3Br1u4VB4mW7z1nA-I295fDIlZIxr83gNFFrj2dRMSXpsILdSo92cEOdP67JediPWqJbUKvPcQW3COY8Hng4x73Q_s-3SVQCjJD5Zt6VO5TgPzcsp4vGUNav9olWg8Ig9N3Q5pUM_chgQKsm8HArOhfEoAMMzFYTWmoP99qNkI5nfintWVJxa_qTk8V-5KV6NVyvAc'
            ),
        ));

        // Ejecutar y obtener respuesta
        $response = curl_exec($curl);

        // Verificar errores en la ejecución de cURL
        if (curl_errno($curl)) {
            throw new Exception("Error en la solicitud cURL: " . curl_error($curl));
        }

        // Cerrar cURL
        curl_close($curl);

        // Decodificar la respuesta JSON
        $response_data = json_decode($response, true);

        // Manejar la respuesta de la API
        if (isset($response_data['status']) && $response_data['status'] === 'Created') {
            // Si la respuesta es exitosa, insertar en la base de datos
            // Conectar a la base de datos
            require '../config/conexion.php'; // Asegúrate de que la conexión está configurada correctamente

            // Preparar el INSERT
            $stmt = $conn->prepare(
                "INSERT INTO numbering_ranges (document, prefix, `from`, `to`, current, resolution_number, start_date, end_date, technical_key, is_expired, is_active, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de inserción: " . $conn->error);
            }

            // Asignar valores predeterminados a los campos opcionales si están vacíos o nulos
            $resolution_number = isset($_POST['resolution_number']) ? $_POST['resolution_number'] : ''; // Usar cadena vacía si no existe
            $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : ''; // Usar cadena vacía si no existe
            $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : ''; // Usar cadena vacía si no existe
            $technical_key = isset($_POST['technical_key']) ? $_POST['technical_key'] : NULL; // Usar NULL si no existe
			$user_id = $_SESSION['user_id'];
            $is_expired = 0;
            $is_active = 1;

            // Bind de parámetros
            $stmt->bind_param(
                "sssssssssssi",
                $_POST['document'], $_POST['prefix'], $_POST['from'], $_POST['to'], $_POST['current'],
                $resolution_number, $start_date, $end_date, $technical_key, $is_expired, $is_active, $user_id
            );

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la inserción en la base de datos: " . $stmt->error);
            }

            // Cerrar la consulta
            $stmt->close();

            // Redirigir en caso de éxito
            header("Location: ../html/numbering-ranges.php?success=1");
            exit();
        } elseif (isset($response_data['message']) && strpos($response_data['message'], 'número de resolución ya está en uso') !== false) {
            // Redirigir si el número de resolución ya existe
            header("Location: ../html/numbering-ranges.php?error=resolution_exists");
            exit();
        } else {
            // Manejar otros errores no especificados
            throw new Exception("Error inesperado en la respuesta de la API.");
        }

    } catch (Exception $e) {
        // Registrar el error en los logs y mostrar mensaje genérico
        error_log($e->getMessage(), 3, "../logs/error.log");
        die("Ocurrió un error. Por favor, revise los logs.");
    }
} else {
    echo "Método no permitido";
}
?>