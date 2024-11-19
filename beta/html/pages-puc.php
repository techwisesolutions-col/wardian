<?php
// Conexión a la base de datos
$host = '145.223.120.184';
$user = 'wardian';
$password = '4vX#oT03rAAbavn53AYC';
$dbname = 'wardian';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener las cuentas ordenadas por código
$sql = "SELECT id, codigo, nombre, nivel, clase, grupo, cuenta, subcuenta FROM puc_colombia WHERE 1 ORDER BY codigo";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("No se encontraron registros en la tabla PUC o hubo un error en la consulta.");
}

// Función para generar el árbol jerárquico
function generarArbol($result) {
    $cuentas = [];

    // Organizar las cuentas por los diferentes niveles
    while ($row = $result->fetch_assoc()) {
        // Usamos clase, grupo, cuenta y subcuenta para organizar el árbol
        $cuentas[$row['clase']][$row['grupo']][$row['cuenta']][$row['subcuenta']] = $row;
    }

    // Recursividad para construir el árbol
    function construirArbol($cuentas, $ruta = "") {
    $html = '<ul>';
    foreach ($cuentas as $clase => $grupos) {
        // Ruta para la clase
        $rutaClase = $ruta . "&clase=" . urlencode($clase);
        $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                  <a href="detalle.php?' . $rutaClase . '"><strong>Clase ' . $clase . '</strong></a>';
        $html .= '<ul style="display:none;">'; // Subniveles ocultos inicialmente
        foreach ($grupos as $grupo => $cuentasGrupo) {
            // Ruta para el grupo
            $rutaGrupo = $rutaClase . "&grupo=" . urlencode($grupo);
            $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                      <a href="detalle.php?' . $rutaGrupo . '"><strong>Grupo ' . $grupo . '</strong></a>';
            $html .= '<ul style="display:none;">';
            foreach ($cuentasGrupo as $cuenta => $subcuentas) {
                // Ruta para la cuenta
                $rutaCuenta = $rutaGrupo . "&cuenta=" . urlencode($cuenta);
                $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                          <a href="detalle.php?' . $rutaCuenta . '"><strong>Cuenta ' . $cuenta . '</strong></a>';
                $html .= '<ul style="display:none;">';
                foreach ($subcuentas as $subcuenta => $detalle) {
                    // Ruta para la subcuenta
                    $rutaSubcuenta = $rutaCuenta . "&subcuenta=" . urlencode($subcuenta);
                    $html .= '<li>
                              <a href="detalle.php?' . $rutaSubcuenta . '">' . $detalle['codigo'] . ' - ' . $detalle['nombre'] . '</a>
                              </li>';
                }
                $html .= '</ul></li>';
            }
            $html .= '</ul></li>';
        }
        $html .= '</ul></li>';
    }
    $html .= '</ul>';
    return $html;
}


    // Construir el árbol comenzando desde la clase
    return construirArbol($cuentas);
}

// Generar el HTML del árbol jerárquico
$treeHtml = generarArbol($result);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUC - Árbol Contable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        ul {
            list-style-type: none;
            padding-left: 20px;
        }
        li {
            margin: 5px 0;
        }
        .toggle {
            cursor: pointer;
            margin-right: 5px;
        }
        .debito {
            color: green;
        }
        .credito {
            color: red;
        }
        .filter-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Plan Único de Cuentas (PUC)</h1>
    <div class="filter-container">
        <label for="filter">Filtrar por nombre o código:</label>
        <input type="text" id="filter" placeholder="Buscar...">
    </div>
    <div id="puc-container">
        <?php echo $treeHtml; ?>
    </div>

    <script>
        // Función para mostrar/ocultar los elementos en el árbol
        function toggleVisibility(event) {
            const toggleIcon = event.target;
            const nextUl = toggleIcon.parentElement.querySelector('ul');
            
            if (nextUl) {
                const isHidden = nextUl.style.display === 'none';
                nextUl.style.display = isHidden ? 'block' : 'none';
                toggleIcon.textContent = isHidden ? '➖' : '➕';
            }
        }

        // Filtrar cuentas por nombre o código
        document.getElementById('filter').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('#puc-container li').forEach(li => {
                const text = li.textContent.toLowerCase();
                li.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
