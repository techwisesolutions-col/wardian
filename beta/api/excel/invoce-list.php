<?php
require '../config/conexion.php';

// Obtener los parámetros de búsqueda y paginación
$busqueda = isset($_GET['search']) ? $_GET['search'] : '';
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// ... (tu código PHP para la consulta, incluyendo la búsqueda y la paginación) ...

// Establecer las cabeceras para la descarga del archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="facturas.xls"');
header('Cache-Control: max-age=0');

// Crear la tabla HTML que se exportará a Excel
echo '<table border="1">';
echo '<tr>';
echo '<th>No. Factura</th>';
echo '<th>Fecha de creación</th>';
echo '<th>Tipo de factura</th>';
echo '<th>Moneda</th>';
echo '<th>line_extension_amount</th>';
echo '<th>tax_exclusive_amount</th>';
echo '<th>payable_amount</th>';
echo '<th>payable_amount</th>';
echo '</tr>';

// Recorrer los resultados de la consulta y generar las filas de la tabla
while ($row = $result->fetch_assoc()) {
  echo '<tr>';
  echo '<td>' . $row['invoice_id'] . '</td>';
  echo '<td>' . $row['issue_date'] . '</td>';
  echo '<td>' . $row['invoice_type_code'] . '</td>';
  echo '<td>' . $row['currency_code'] . '</td>';
  echo '<td>$' . number_format($row['line_extension_amount'], 2, '.', ',') . '</td>';
  echo '<td>$' . number_format($row['tax_amount'], 2, '.', ',') . '</td>';
  echo '<td>$' . number_format($row['tax_amount_rete_renta'], 2, '.', ',') . '</td>';
  echo '<td>$' . number_format($row['total'], 2, '.', ',') . '</td>';
  echo '</tr>';
}

echo '</table>';
?>