<?php

// Initialize the session
ini_set('session.gc_probability', 0);
session_start();

require_once 'config.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Capacitación en español, todos los cursos que buscas">
    <meta name="author" content="mygsystems">

    <title>Clínica Dematológica</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css?v=13" rel="stylesheet">
    <link href="css/sb-admin-2.css?v=43" rel="stylesheet">
    <link rel="shortcut icon" href="image/logo.png" />
    <link href="css/font-awesome/css/all.css" rel="stylesheet" type="text/css">
    <!-- Slide Categorías -->
    <link rel="stylesheet" href="Tables/jquery.dataTables.css">
    <!-- alertas -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body 
<?php
if(isset($_SESSION['Usuario']) && !empty($_SESSION['Usuario']) && isset($_GET["venta"])) 
{ 
    echo "onload=cargaVenta(".$_GET["venta"].")";
}
       ?>
    >

    <div id="wrapper">
        <div id="banner">
            <div class="textobanner">
                <h1>
                    CLÍNICA DERMATOLÓGICA
                </h1>
                <p>
                    Dra. Melina Lois
                </p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="">
                <button id='btnmenu' type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

                <li class="dropdown">

                    <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                        <?php
                        if (isset($_SESSION['Usuario']) && !empty($_SESSION['Usuario'])) {
                            echo "
                            <span class='nombre'>" . $_SESSION["Nombre"] . " " . $_SESSION["Apellido"] . " </span>";
                        }
                        ?>
                        <i class=' fa fa-user fa-fw'></i> <i class='fa fa-caret-down'></i>
                    </a>

                    <ul class="dropdown-menu dropdown-user">
                        <?php
                        if (isset($_SESSION['Usuario']) && !empty($_SESSION['Usuario'])) {
                            echo "
                        <li>
                            <a onclick=paginaPrincipal('modificarDatos.php')><i class='fas fa-exchange-alt'></i> Modificar Datos</a>
                        </li>
             
                        <li>
                            <a href='login.php'><i class='fa fa-sign-out-alt'></i> Logout</a>
                        </li>";
                        } else {
                            echo "
                    
                        <li>
                            <a href='login.php'><i class='fas fa-exchange-alt'></i> Ingresar</a>
                        </li>";
                        }
                        ?>
                    </ul>
                </li>

            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-collapse" role="navigation">
                <div class="navbar-collapse pull-left">
                    <ul class="nav" id="side-menu">
                        <?php
                        if (isset($_SESSION['Usuario']) || !empty($_SESSION['Usuario'])) {
                            switch ($_SESSION["Perfil"]) {
                                case 'admin':
                                case 'medico':
                                    echo "
                        <li>
                            <a href='#'><i class='fas fa-cogs'></i> CONFIGURACIÓN <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='ObraSocial/index.php'> <i class='fas fa-folder-open'></i> Obras Sociales</a>
                                </li>
                                <li>
                                    <a href='FormaPago/index.php'> <i class='far fa-money-bill-alt'></i> Formas de pago</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-user-cog'></i> PERSONAL <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Medico/index.php'> <i class='fas fa-user-md'></i> Administración de Médicos</a>
                                </li>
                                <li>
                                    <a href='Secretaria/index.php'> <i class='fas fa-user'></i> Administración de Secretarias</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-briefcase-medical'></i> TRATAMIENTOS <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Tratamiento/index.php'> <i class='fas fa-folder'></i> Administración de tratamientos</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-pills'></i> PRODUCTOS <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Proveedor/index.php'> <i class='fas fa-truck'></i> Administración de proveedores</a>
                                </li>
                                <li>
                                    <a href='Producto/index.php'> <i class='fas fa-folder'></i> Administración de productos</a>
                                </li>
                                <li>
                                    <a href='Compra/index.php'> <i class='fas fa-cart-plus'></i> Registro de compras</a>
                                </li>
                                <li>
                                    <a href='Producto/stock.php'> <i class='fas fa-clipboard-list'></i> Control de stock</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-user-cog'></i> PACIENTES <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Paciente/index.php'> <i class='fas fa-user-injured'></i> Administración de pacientes</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-clock'></i> TURNOS <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Horarios/index.php'><i class='fas fa-user-clock'></i> Administración de horarios </span></a>
                                </li>
                                <li>
                                    <a onclick=paginaPrincipal('turnos.php')><i class='fas fa-plus'></i> Nuevo Turno </span></a>
                                </li>
                                    <li>
                                    <a href='Turno/index.php'><i class='fas fa-calendar'></i> Agenda de turnos </span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-file-invoice-dollar'></i> REGISTRO <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                             
                                <li>
                                    <a onclick=nuevaVenta()><i class='fas fa-plus'></i> Venta </span></a>
                                </li>
                                <li>
                                    <a onclick=paginaPrincipal('ventas.php')><i class='fas fa-folder'></i> Listado </span></a>
                                </li>
                                <li>
                                    <a href='Planilla/index.php'><i class='fas fa-list'></i> Planilla </span></a>
                                </li>
                                
                            </ul>
                        </li>";
                                    break;
                                case 'secretaria':
                                    echo "
                                <li>
                                    <a href='#'><i class='fas fa-pills'></i> PRODUCTOS <span class='fas fa-angle-double-right'></span></a>
                                    <ul class='nav nav-second-level'>
                                        <li>
                                            <a href='Producto/stock.php'> <i class='fas fa-clipboard-list'></i> Control de stock</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href='#'><i class='fas fa-user-cog'></i> PACIENTES <span class='fas fa-angle-double-right'></span></a>
                                    <ul class='nav nav-second-level'>
                                        <li>
                                            <a href='Paciente/index.php'> <i class='fas fa-user-injured'></i> Administración de pacientes</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href='#'><i class='fas fa-clock'></i> TURNOS <span class='fas fa-angle-double-right'></span></a>
                                    <ul class='nav nav-second-level'>
                                        <li>
                                            <a href='Horarios/index.php'><i class='fas fa-user-clock'></i> Administración de horarios </span></a>
                                        </li>
                                        <li>
                                            <a onclick=paginaPrincipal('turnos.php')><i class='fas fa-plus'></i> Nuevo Turno </span></a>
                                        </li>
                                         <li>
                                            <a href='Turno/index.php'><i class='fas fa-calendar'></i> Agenda de turnos </span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href='#'><i class='fas fa-file-invoice-dollar'></i> REGISTRO <span class='fas fa-angle-double-right'></span></a>
                                    <ul class='nav nav-second-level'>
                                    
                                        <li>
                                            <a onclick=nuevaVenta()><i class='fas fa-plus'></i> Venta </span></a>
                                        </li>
                                        <li>
                                            <a onclick=paginaPrincipal('ventas.php')><i class='fas fa-folder'></i> Listado </span></a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                ";
                                    break;
                                case 'submedico':
                                 echo "
                                    <li>
                                        <a href='#'><i class='fas fa-user-cog'></i> PACIENTES <span class='fas fa-angle-double-right'></span></a>
                                        <ul class='nav nav-second-level'>
                                            <li>
                                                <a href='Paciente/index.php'> <i class='fas fa-user-injured'></i> Administración de pacientes</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href='#'><i class='fas fa-clock'></i> TURNOS <span class='fas fa-angle-double-right'></span></a>
                                        <ul class='nav nav-second-level'>
                                            <li>
                                                <a href='Turno/index.php'><i class='fas fa-calendar'></i> Agenda de turnos </span></a>
                                            </li>
                                        </ul>
                                    </li>";
                                default:
                                    # code...
                                    break;
                            } //switch perfiles
                        } else {
                            echo "<li>
                            <a href='login.php'><i class='fas fa-exchange-alt'></i> INGRESAR</a>
                        </li>";
                        }


                        ?>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">

        </div>

        <!-- /#page-wrapper -->
        <div class="footer">
            Desarrollado por <a href="https://www.mygsystems.com">M&G Systems.com</a>
        </div>
    </div>
    <!-- /#wrapper -->
    <script>
        //TODAS LAS FUNCIONES PARA CARGAR LAS PAGINAS SIN REGARGAR
        //LLama a la funcion cada 10 seg
        // setInterval("irPagina('dashboard.php')",20000);

        //Funcion para no cambiar de página
        function irPaginaPrincipal(pag) {
            $("#page-wrapper").load(pag);
            document.getElementById('btnmenu').click();
        }

        function paginaPrincipal(pag, param = "") {
            $("#page-wrapper").load(pag, param);
            var element = document.querySelector("#page-wrapper");
            // scroll to element
            element.scrollIntoView();
        }

        //Funcion para no cambiar de página
        function irSubPagina(pag) {
            $("#sub-pagina").load(pag);
            document.getElementById('btnmenu').click();
        }

        function subPagina(pag, param) {
            // alert(parametros);
            $("#sub-pagina").load(pag, param);
        }

        function recargaPedido() {
            // alert(parametros);
            $("#cantidadpedidos").load('itemspedido.php');
        }

        function openmenu() {
            document.getElementById('btnmenu').click();
        }
    </script>

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Acciones para pedidos -->
    <script src="js/Acciones/acciones.js?v=16"></script>
    <script src="Tables/jquery.dataTables.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sb-admin-2.js?v=2"></script>
    <script src="js/metisMenu.min.js"></script>
    <!-- Alertas -->
    <script>
        function loader() {
            $("#loader").show();
        }
    </script>
    </body>

</html>