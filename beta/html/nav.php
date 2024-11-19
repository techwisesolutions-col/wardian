<?php include '../config/conexion.php'; ?>

<nav class="iq-sidebar-menu">
    <ul id="iq-sidebar-toggle" class="iq-menu">
        <li class="active">
            <a href="./dashboard.php" class="iq-waves-effect" aria-expanded="true"><span class="ripple rippleEffect"></span><i class="ri-dashboard-line iq-arrow-left"></i><span>Dashboard</span></a>
        </li>
    
        <li>
            <a href="#userinfo" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="las la-user-tie iq-arrow-left"></i><span>Clientes</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="userinfo" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./form-client.php"><i class="ri-add-circle-line"></i>Registro</a></li>
            <li><a href="./client-list.php"><i class="ri-group-line"></i>Registrados</a></li>
            </ul>
        </li>
      
      
        <li>
            <a href="#third" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-user-location-line iq-arrow-left"></i><span>Terceros</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="third" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./form-third.php"><i class="ri-add-circle-line"></i>Registro</a></li>
            <li><a href="./third-list.php"><i class="ri-file-list-3-line"></i>Registrados</a></li>
            </ul>
        </li>
      
       <li>
            <a href="#inventary" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-list-ordered iq-arrow-left"></i><span>Inventario</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="inventary" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./form-inventory.php"><i class="ri-add-circle-line"></i>Registro</a></li>
            <li><a href="./third-list.php"><i class="ri-file-list-3-line"></i>Registrados</a></li>
            </ul>
        </li>
      
       <li>
            <a href="#myinvoce" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-bill-line iq-arrow-left"></i><span>Facturación</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="myinvoce" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./create-client-invoice.php"><i class="ri-add-circle-line"></i>Crear Factura</a></li>
            <li><a href="./invoce-list.php"><i class="ri-file-list-3-line"></i>Mis Facturas</a></li>
            <li><a href="./numbering-ranges.php"><i class="ri-list-ordered"></i>Crear Rango de Numeración</a></li>
            </ul>
        </li>
      
             
      <li>
            <a href="#myinvoce" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-file-list-2-line iq-arrow-left"></i><span>Documento Soporte</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="myinvoce" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./#"><i class="ri-add-circle-line"></i>Crear Documento</a></li>
            <li><a href="./#"><i class="ri-file-list-3-line"></i>Mis Documentos</a></li>
            </ul>
        </li>
      
      
        <li>
            <a href="#myinvoce" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-store-line iq-arrow-left"></i><span>Nomina</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="myinvoce" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./#"><i class="ri-add-circle-line"></i>Cargar Nomina</a></li>
            <li><a href="./#"><i class="ri-file-list-3-line"></i>Mis Cargues</a></li>
            </ul>
        </li>
      
      
        <li>
            <a href="#puc" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="ri-calculator-line iq-arrow-left"></i><span>Contabilidad</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="puc" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="./puc.php"><i class="ri-add-circle-line"></i>Lista Cuentas Contables</a></li>
            </ul>
        </li>

        
        <li>
            <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-file-line iq-arrow-left"></i><span>Documentos</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
        </li>

        <li>
            <a href="#uvt" class="iq-waves-effect" data-toggle="collapse" aria-expanded="false"><span class="ripple rippleEffect"></span><i class="las la-user-tie iq-arrow-left"></i><span>UVT</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
            <ul id="uvt" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" style="">
            <li><a href="form-client.php"><i class="las la-id-card-alt"></i>Registrar</a></li>
            <li><a href="client-list.php"><i class="las la-edit"></i>Registrados</a></li>
            </ul>
        </li>

        <li>
            <a href="#" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-customer-service-2-line iq-arrow-left"></i><span>Soporte</span></a>
        </li>
        
    </ul>
</nav>