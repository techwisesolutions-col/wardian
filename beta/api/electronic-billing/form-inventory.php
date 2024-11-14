<?php
session_start();
require '../../config/conexion.php'; // Conexión a la base de datos

try {
    // Verificar que todos los campos fueron enviados
    $required_fields = [
        'product_type', 'product_name', 'product_detail', 'product_unit',
        'long_description', 'price_list', 'include_iva', 'tax_charge'
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
        header("Location: ../html/form-product.php?error=missing_fields&fields=$fields");
        exit();
    }

    // Verificar si el producto ya existe (ejemplo: validación por nombre y tipo)
    $product_name = $_POST['product_name'];
    $product_type = $_POST['product_type'];
    $query = "SELECT id FROM products WHERE product_name = ? AND product_type = ?";
    $checkStmt = $conn->prepare($query);
    if (!$checkStmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $checkStmt->bind_param("ss", $product_name, $product_type);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        header("Location: ../html/form-product.php?error=product_exists");
        exit();
    }

    // Preparar la inserción del producto
    $stmt = $conn->prepare(
        "INSERT INTO products (product_type, product_name, product_detail, product_unit, long_description, price_list, include_iva, tax_charge) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar la inserción: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssss",
        $_POST['product_type'], $_POST['product_name'], $_POST['product_detail'], $_POST['product_unit'],
        $_POST['long_description'], $_POST['price_list'], $_POST['include_iva'], $_POST['tax_charge']
    );

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la inserción: " . $stmt->error);
    }

    $product_id = $stmt->insert_id; // Obtener el ID del producto insertado

    // Manejar las imágenes del producto
    if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
        $image_files = $_FILES['product_images'];
        $upload_dir = '../../uploads/'; // Directorio para guardar las imágenes

        // Verificar si el directorio existe, sino crearlo
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Iterar sobre las imágenes y subirlas
        for ($i = 0; $i < count($image_files['name']); $i++) {
            $image_name = basename($image_files['name'][$i]);
            $image_path = $upload_dir . $image_name;

            // Subir la imagen
            if (move_uploaded_file($image_files['tmp_name'][$i], $image_path)) {
                // Insertar la ruta de la imagen en la base de datos
                $sql_image = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
                $stmt_image = $conn->prepare($sql_image);
                $stmt_image->bind_param("is", $product_id, $image_path);
                $stmt_image->execute();
                $stmt_image->close();
            }
        }
    }

    // Redirigir en caso de éxito
    header("Location: ../../html/form-inventory.php?success=1");
    exit();

} catch (Exception $e) {
    // Registrar el error en los logs y mostrar mensaje genérico
    error_log($e->getMessage(), 3, "../../logs/error.log");
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
