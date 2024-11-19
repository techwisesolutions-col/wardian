<!doctype html>
<?php
// Incluir configuración y conexión a la base de datos
require '../config/conexion.php';

// Consulta para obtener las cuentas ordenadas por código
$sql = "SELECT id, codigo, nombre, nivel, clase, grupo, cuenta, subcuenta FROM puc_colombia WHERE 1 ORDER BY codigo";
$result = $conn->query($sql);

// Verificar si se obtuvieron registros
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

    // Función recursiva para construir el árbol
    function construirArbol($cuentas, $ruta = "") {
        $html = '<ul>';
        foreach ($cuentas as $clase => $grupos) {
            // Ruta para la clase
            $rutaClase = $ruta . "&clase=" . urlencode($clase);
            $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                      <strong>Clase ' . $clase . '</strong>';
            $html .= '<ul style="display:none;">'; // Subniveles ocultos inicialmente
            foreach ($grupos as $grupo => $cuentasGrupo) {
                // Ruta para el grupo
                $rutaGrupo = $rutaClase . "&grupo=" . urlencode($grupo);
                $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                          <strong>Grupo ' . $grupo . '</strong>';
                $html .= '<ul style="display:none;">';
                foreach ($cuentasGrupo as $cuenta => $subcuentas) {
                    // Ruta para la cuenta
                    $rutaCuenta = $rutaGrupo . "&cuenta=" . urlencode($cuenta);
                    $html .= '<li><span class="toggle" onclick="toggleVisibility(event)">➕</span> 
                              <a href="details-puc.php?' . $rutaCuenta . '"><strong>Cuenta ' . $cuenta . '</strong></a>';
                    $html .= '<ul style="display:none;">';
                    foreach ($subcuentas as $subcuenta => $detalle) {
                        // Ruta para la subcuenta
                        $rutaSubcuenta = $rutaCuenta . "&subcuenta=" . urlencode($subcuenta);
                        $html .= '<li>
                                  <a href="details-puc.php?' . $rutaSubcuenta . '">' . $detalle['codigo'] . ' - ' . $detalle['nombre'] . '</a>
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

// Cerrar la conexión a la base de datos
$conn->close();
?>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WARDIAN - SYSTEM</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="css/typography.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    

    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="iq-sidebar">
            <div class="iq-navbar-logo d-flex justify-content-between">
                <a href="index.html" class="header-logo">
                    <img src="images/logo.png" class="img-fluid rounded" alt="">
                    <span>Nexo</span>
                </a>
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-menu-line"></i></div>
                        <div class="hover-circle"><i class="ri-close-fill"></i></div>
                    </div>
                </div>
            </div>
            <div id="sidebar-scrollbar">
                <?php include './nav.php'; ?>
                <div class="p-3"></div>
            </div>
        </div>

        <!-- TOP Nav Bar -->
        <?php include './header.php'; ?>
        <!-- TOP Nav Bar END -->

        <!-- Page Content -->
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <!-- Alerta de error o éxito -->
                    <div class="col-sm-12">
                        <?php if (isset($_GET['error']) && $_GET['error'] == 'missing_id'): ?>
                            <div class="alert alert-danger" role="alert">
                                <div class="iq-alert-icon">
                                    <i class="ri-alert-line"></i>
                                </div>
                                <div class="iq-alert-text">
                                    <strong>Error:</strong> No se proporcionó un ID válido para eliminar.
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
                            <div class="alert alert-danger" role="alert">
                                <div class="iq-alert-icon">
                                    <i class="ri-alert-line"></i>
                                </div>
                                <div class="iq-alert-text">
                                    <strong>Error:</strong> No se pudo eliminar el cliente. Inténtalo de nuevo.
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <div class="alert alert-success" role="alert">
                                <div class="iq-alert-icon">
                                    <i class="ri-check-line"></i>
                                </div>
                                <div class="iq-alert-text">
                                    <strong>¡Éxito!</strong> Cliente eliminado con éxito.
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Plan Único de Cuentas (PUC)</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-md-6">
                                        <form class="mr-3 position-relative" method="GET" action="">
                                            <div class="form-group mb-0">
                                                <input type="search" class="form-control" id="filter" name="search" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <div id="puc-container">
                                    <div class="row">
                                        <div class="col-6">
                                            <div id="list-puc">
                                                <?php echo $treeHtml; ?>
                                            </div>
                                        </div>
                                        <div class="col-6" id="form-puc">
                                            <div class="iq-card-body">
                                                <form method="POST" action="../api/details-auxiliar-puc.php" novalidate>
                                                    <div class="form-row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="empresa">Clase</label>
                                                            <input type="text" class="form-control" id="clase" name="clase" value="<?php echo $nombre_clase; ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="nit">Grupo</label>
                                                            <input type="text" class="form-control" id="grupo" name="grupo" value="<?php echo $nombre_grupo; ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="nit">Cuenta</label>
                                                            <input type="text" class="form-control" id="cuenta" name="cuenta" value="<?php echo $nombre_cuenta; ?>" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="nit">Subcuenta</label>
                                                            <input type="text" class="form-control" id="subcuenta" name="subcuenta" value="<?php echo $nombre_subcuenta; ?>" required>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>

    <script>
        // Función para mostrar u ocultar subniveles del árbol
        function toggleVisibility(event) {
            const toggleIcon = event.target;
            const nextUl = toggleIcon.parentElement.querySelector('ul');
            
            if (nextUl) {
                const isHidden = nextUl.style.display === 'none';
                nextUl.style.display = isHidden ? 'block' : 'none';
                toggleIcon.textContent = isHidden ? '➖' : '➕';
            }
        }

        // Función para cargar los detalles de la subcuenta dinámicamente
        function loadSubcuentaData(ruta) {
            const subcuentaDiv = document.getElementById('subcuenta-details');
            subcuentaDiv.innerHTML = '<p>Cargando...</p>';

            // Realizar solicitud AJAX para obtener los datos
            $.get(ruta, function(data) {
                subcuentaDiv.innerHTML = data;
            });
        }
    </script>
</body>
</html>
