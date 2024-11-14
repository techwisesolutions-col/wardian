<?php

// Configuración de la base de datos
$host = '145.223.120.184';
$db = 'wardian';
$user = 'wardian';
$pass = '4vX#oT03rAAbavn53AYC';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Manejo de errores de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer la zona horaria a Colombia
date_default_timezone_set('America/Bogota');

// Obtener la fecha y hora actual
$dateLocal = date('Y-m-d H:i:s');

// Obtener el año
$dateYear = date('Y');

?>