<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
libxml_use_internal_errors(true);  // Manejo de errores XML

$noDocumemento = '/files/'.$_GET['noDocumemento'];

// Conexión a la base de datos utilizando PDO
$dsn = 'mysql:host=145.223.120.184;dbname=wardian;charset=utf8mb4';
$username = 'wardian';
$password = '4vX#oT03rAAbavn53AYC';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error en la conexión: ' . $e->getMessage());
}

// Cargar el XML
$xml = simplexml_load_file(__DIR__ . $noDocumemento, 'SimpleXMLElement', LIBXML_NOCDATA);
if ($xml === false) {
    echo "Errores al cargar el XML:\n";
    foreach (libxml_get_errors() as $error) {
        echo "\tLínea {$error->line}: {$error->message}\n";
    }
    exit;
}

// Registrar namespaces para XPath
$xml->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
$xml->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

// Función para obtener valores seguros con XPath
function getXPathValue($xml, $path) {
    $result = $xml->xpath($path);
    return $result && isset($result[0]) ? (string) $result[0] : 'N/A';
}

// **1. Inserción en la tabla `invoices`**
$invoiceProfileID = getXPathValue($xml, '//cbc:ProfileID');
$invoiceID = getXPathValue($xml, '//cbc:ID');
$uuid = getXPathValue($xml, '//cbc:UUID');
$issueDate = getXPathValue($xml, '//cbc:IssueDate');
$issueTime = '2024-11-05 00:00:00';
$typeCode = getXPathValue($xml, '//cbc:InvoiceTypeCode');
$currencyCode = getXPathValue($xml, '//cbc:DocumentCurrencyCode');
$profileExecutionID = getXPathValue($xml, '//cbc:ProfileExecutionID');
$lineExtensionAmount = getXPathValue($xml, '//cac:LegalMonetaryTotal/cbc:LineExtensionAmount');
$taxExclusiveAmount = getXPathValue($xml, '//cac:LegalMonetaryTotal/cbc:TaxExclusiveAmount');
$payableAmount = getXPathValue($xml, '//cac:LegalMonetaryTotal/cbc:PayableAmount');

$stmt = $pdo->prepare("
    INSERT INTO invoices (profile_id, invoice_id, uuid, issue_date, issue_time, invoice_type_code, currency_code, profile_execution_id, 
                          line_extension_amount, tax_exclusive_amount, payable_amount) 
    VALUES (:profile_id, :invoice_id, :uuid, :issue_date, :issue_time, :invoice_type_code, :currency_code, :profile_execution_id, 
            :line_extension_amount, :tax_exclusive_amount, :payable_amount)
");
$stmt->execute([
    ':profile_id' => $invoiceProfileID,
    ':invoice_id' => $invoiceID,
    ':uuid' => $uuid,
    ':issue_date' => $issueDate,
    ':issue_time' => $issueTime,
    ':invoice_type_code' => $typeCode,
    ':currency_code' => $currencyCode,
    ':profile_execution_id' => $profileExecutionID,
    ':line_extension_amount' => $lineExtensionAmount = ($lineExtensionAmount === 'N/A') ? '0' : $lineExtensionAmount,
    ':tax_exclusive_amount' => $taxExclusiveAmount = ($taxExclusiveAmount === 'N/A') ? '0' : $taxExclusiveAmount,
    ':payable_amount' => $payableAmount = ($payableAmount === 'N/A') ? '0' : $payableAmount,
]);
$invoiceId = $pdo->lastInsertId();  // Obtener el ID de la factura

// **Inserción en `withholding_tax_totals`**
foreach ($xml->xpath('//cac:WithholdingTaxTotal/cac:TaxSubtotal') as $tax) {
    $taxAmount = getXPathValue($tax, 'cbc:TaxAmount');
    $taxableAmount = getXPathValue($tax, 'cbc:TaxableAmount');
    $percent = getXPathValue($tax, 'cac:TaxCategory/cbc:Percent');
    $taxSchemeID = getXPathValue($tax, 'cac:TaxCategory/cac:TaxScheme/cbc:ID');
    $taxSchemeName = getXPathValue($tax, 'cac:TaxCategory/cac:TaxScheme/cbc:Name');

    $stmt = $pdo->prepare("
        INSERT INTO withholding_tax_totals (invoice_id, tax_amount, taxable_amount, percent, tax_scheme_id, tax_scheme_name)
        VALUES (:invoice_id, :tax_amount, :taxable_amount, :percent, :tax_scheme_id, :tax_scheme_name)
    ");
    $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':tax_amount' => $taxAmount = ($taxAmount === 'N/A') ? '0' : $taxAmount,
        ':taxable_amount' => $taxableAmount = ($taxableAmount === 'N/A') ? '0' : $taxableAmount,
        ':percent' => $percent,
        ':tax_scheme_id' => $taxSchemeID,
        ':tax_scheme_name' => $taxSchemeName
    ]);
}

// **2. Inserción en `invoice_lines`**
foreach ($xml->xpath('//cac:InvoiceLine') as $line) {
    $lineID = getXPathValue($line, 'cbc:ID');
    $quantity = getXPathValue($line, 'cbc:InvoicedQuantity');
    $unitCode = (string) $line->cbc->InvoicedQuantity['unitCode'];
    $extensionAmount = getXPathValue($line, 'cbc:LineExtensionAmount');
    $taxAmount = getXPathValue($line, 'cac:TaxTotal/cbc:TaxAmount');
    $description = getXPathValue($line, 'cac:Item/cbc:Description');
    $percent = (float) getXPathValue($line, 'cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:Percent');
    $nameTax = getXPathValue($line, 'cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:Name');


    $stmt = $pdo->prepare("
        INSERT INTO invoice_lines (invoice_id, line_id, invoiced_quantity, unit_code, line_extension_amount, tax_amount, description, tax_percent, tax_scheme_name)
        VALUES (:invoice_id, :line_id, :invoiced_quantity, :unit_code, :line_extension_amount, :tax_amount, :description, :percent, :name_tax)
    ");
    $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':line_id' => $lineID,
        ':invoiced_quantity' => $quantity,
        ':unit_code' => $unitCode,
        ':line_extension_amount' =>  $extensionAmount = ($extensionAmount === 'N/A') ? '0' : $extensionAmount,
        ':tax_amount' => $taxAmount = ($taxAmount === 'N/A') ? '0' : $taxAmount,
        ':description' => $description,
        ':percent' => $percent,
        ':name_tax' => $nameTax
    ]);
}

// **3. Inserción en `parties`**
function insertParty($pdo, $invoiceId, $partyType, $name, $city, $address, $country, $contact, $phone, $email) {
    $stmt = $pdo->prepare("
        INSERT INTO parties (invoice_id, party_type, name, city, address_line, country_name, contact_name, telephone, email)
        VALUES (:invoice_id, :party_type, :name, :city, :address_line, :country_name, :contact_name, :telephone, :email)
    ");
    $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':party_type' => $partyType,
        ':name' => $name,
        ':city' => $city,
        ':address_line' => $address,
        ':country_name' => $country,
        ':contact_name' => $contact,
        ':telephone' => $phone,
        ':email' => $email
    ]);
}

// Insertar proveedor y cliente
insertParty($pdo, $invoiceId, 'supplier', 'Proveedor', 'Medellín', 'CALLE 39', 'Colombia', 'Contacto', '604324355', 'supplier@example.com');
insertParty($pdo, $invoiceId, 'customer', 'Cliente', 'Sabaneta', 'CL 60', 'Colombia', 'Contacto Cliente', '604489746', 'customer@example.com');

echo "Proceso completado.\n";
?>
