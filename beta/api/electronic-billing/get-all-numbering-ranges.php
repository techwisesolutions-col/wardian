<?php
session_start();
$user_id = $_SESSION['user_id'];

// Inicializar cURL
$curl = curl_init();

// Configurar opciones de cURL
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api-sandbox.factus.com.co/v1/numbering-ranges',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZDYwYTVjNy04Y2Q5LTQzNDMtOTdjMy0xNWU2YzRkNGQ4ZjMiLCJqdGkiOiI3NTg5MDYwN2MwZDQ1ZmEzYjZkZmQyODU3Njk3NDFiZDcxOTllZmQ2NWM0MjNhM2ZhMzc3YjFmMTlhMmY0Y2Y2N2UyMDI2MmEwM2U4MjNiMCIsImlhdCI6MTczMTA5Mjk2Ni40MjgzMiwibmJmIjoxNzMxMDkyOTY2LjQyODMyMywiZXhwIjoxNzMxMDk2NTY2LjQxMzkwOSwic3ViIjoiMyIsInNjb3BlcyI6W119.thbmiUGGCNDLqVKe2hb0Ue0ZMhe_R70yNGV5ZFccn_kNE_BZ8UwrvihAdb5-GL51owymcqLrR9gSn9SfD_6Lc0_h8laydfHQ7pDPQOiHBZBbX0ko3SWNeHMRXFRGVihz19cz2x4X5iapQup1Q018VaUZJ4Rc7gknTU2MrKPECGckJ6XWJxouNo8C6R8RE8lMg338K_KIMtvx2bZMrrChnEsu5uLS85Y7mtG6c3lFx70CzIX-Ixxkm_8pru69YaFd21_TQoQY9DOz5qHKza_RrBDnEPRVmLf-0d51HO5EOfjWMmi7nojJF56EEq72CzY52dV9E9KUBaq1nmytk1EHnfiCbbOAxtjWfOpEnJlZ6ThBXiuDJgtaOyRl8QMZkqHDT0C3JD9OTRIGNJN1HpzENTkAWk-KTTlyDTa3Bkd6bZBl8f9rZ_lcH-ucezBp_TiJnFfpJwxNKhiSScZoIjUuOfoacgV5-a_GffSCw3Br1u4VB4mW7z1nA-I295fDIlZIxr83gNFFrj2dRMSXpsILdSo92cEOdP67JediPWqJbUKvPcQW3COY8Hng4x73Q_s-3SVQCjJD5Zt6VO5TgPzcsp4vGUNav9olWg8Ig9N3Q5pUM_chgQKsm8HArOhfEoAMMzFYTWmoP99qNkI5nfintWVJxa_qTk8V-5KV6NVyvAc'
    ),
));

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);

// Verificar si hubo un error en la solicitud cURL
if ($response === false) {
    die('cURL Error: ' . curl_error($curl));
}

// Obtener código de respuesta HTTP
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Cerrar la conexión cURL
curl_close($curl);


// Decodificar la respuesta JSON
$data = json_decode($response, true);

// Función para validar si la fecha es correcta
function validateDate($date) {
    $date_time = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    return $date_time && $date_time->format('Y-m-d H:i:s') === $date;
}

// Verificar si los datos se recibieron correctamente
if ($data && isset($data['data'])) {
    // Conexión a la base de datos
    require '../../config/conexion.php'; // Asegúrate de que la conexión esté configurada correctamente

    // Preparar la consulta de inserción
    $stmt = $conn->prepare(
        "INSERT INTO numbering_ranges (document, prefix, `from`, `to`, current, resolution_number, start_date, end_date, technical_key, is_expired, is_active, created_at, updated_at, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Error al preparar la consulta de inserción: " . $conn->error);
    }

    // Iterar sobre los datos
    foreach ($data['data'] as $item) {
        // Asignar valores
        if ($item['document'] == 'Factura de Venta'):
             $document = '21';
        elseif ($document == 'Nota Crédito'):
             $document = '22';
        elseif ($document == 'Nota Débito'):
             $document = '23';
        elseif ($document == 'Documento Soporte'):
             $document = '24';
        elseif ($document == 'Nota de Ajuste Documento Soporte'):
             $document = '25';
        elseif ($document == 'Nómina'):
             $document = '26';
        elseif ($document == 'Nota de Ajuste Nómina'):
             $document = '27';
        elseif ($document == 'Nota de eliminación de nómina'):
             $document = '28';
        endif;
      
        $prefix = $item['prefix'];
        $from = $item['from'];
        $to = $item['to'];
        $current = $item['current'];
        $resolution_number = $item['resolution_number'] ?? ''; // Si está vacío, asignamos cadena vacía
        $start_date = $item['start_date'];
        $end_date = $item['end_date'];
        $technical_key = $item['technical_key'] ?? NULL; // Si es nulo, asignamos NULL
        $is_expired = $item['is_expired'] ? 1 : 0;
        $is_active = $item['is_active'];

        // Validar las fechas 'created_at' y 'updated_at'
        $created_at = (validateDate($item['created_at'])) ? $item['created_at'] : NULL;
        $updated_at = (validateDate($item['updated_at'])) ? $item['updated_at'] : NULL;

        // Bind de parámetros
        $stmt->bind_param(
            "ssssssssssiiss",
            $document, $prefix, $from, $to, $current, 
            $resolution_number, $start_date, $end_date, 
            $technical_key, $is_expired, $is_active, 
            $created_at, $updated_at, $user_id
        );

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("Error al insertar datos: " . $stmt->error);
        }
    }

    // Cerrar la consulta
    $stmt->close();

    // Redirigir en caso de éxito
    header("Location: ../../html/numbering-ranges.php?success=2");
} else {
    // Si no se obtuvieron datos o hubo un error con el JSON
    echo "No se pudo obtener o procesar los datos.";
}
?>
