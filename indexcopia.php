<?php
require_once 'config.php';

// Initialize the session
ini_set('session.gc_probability', 0);
session_start();
// If session variable is not set it will redirect to login page
//AHORA LA PAGINA DE INICIO VA A SER EL PUNTO DE PARTIDA
//SIN PÁGINA DE LOGIN
// if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
//     header("location: login.php");
//     exit;
// }
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="mygsystems">

    <title>Verdulería Super Verde</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css?v=5" rel="stylesheet">
    <link href="css/sb-admin-2.css?v=6" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.png" />
    <!-- <link rel="stylesheet" href="css/estiloprincipal.css"> -->
    <link href="css/font-awesome/css/all.css" rel="stylesheet" type="text/css">
    <!-- <script src="css/font-awesome/js/all.js"></script> -->
    <!-- Slide Categorías -->
    <link rel="stylesheet" href="Tables/jquery.dataTables.css?v=2">
    <!-- alertas -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body onload="openmenu()">

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button id='btnmenu' type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-brand">VERDULERÍA SUPER VERDE</div>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">


                <li class="dropdown">

                    <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                        <?php
                        if (isset($_SESSION['Usuario']) || !empty($_SESSION['Usuario'])) {
                            echo "
                            <span class='nombre'>" . $_SESSION["Nombre"] . " " . $_SESSION["Apellido"] . " </span>";
                        }
                        ?>
                        <i class=' fa fa-user fa-fw'></i> <i class='fa fa-caret-down'></i>
                    </a>

                    <ul class="dropdown-menu dropdown-user">
                        <?php
                        if (isset($_SESSION['Usuario']) || !empty($_SESSION['Usuario'])) {
                         
                            echo "
                            <li><a onclick=paginaPrincipal('cambioNombre.php')><i class='fas fa-exchange-alt'></i> Modificar Datos</a>
                            </li>
                            <li><a onclick=desloguear()><i class='fa fa-sign-out-alt'></i> Logout</a>
                            </li>";
                        } else {
                            echo "
                            <li><a onclick=href='login.php'><i class='fas fa-exchange-alt'></i> Ingresar</a>
                            </li>
                            <li><a onclick=paginaPrincipal('registrocliente.php')><i class='fa fa-sign-out-alt'></i>Registrarse</a>
                            </li>";
                        }
                        ?>
                    </ul>
                </li>
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php
                        if (isset($_SESSION['Usuario']) && !empty($_SESSION['Usuario']) && $_SESSION["Perfil"] == 'admin') {
                            echo "
                        <li>
                            <a href='#'><i class='fas fa-cogs'></i> CONFIGURACIÓN <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Zona/index.php'> <i class='fas fa-table'></i> Administración de Zonas</a>
                                </li>
                                <li>
                                    <a href='Configuracion/index.php'> <i class='fas fa-cog'></i> Configuración de ventas</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-apple-alt'></i> ADMINISTRACIÓN DE PRODUCTOS <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='Categoria/index.php'> <i class='fas fa-folder-open'></i> Categorías</a>
                                </li>
                                <li>
                                    <a href='Producto/index.php'> <i class='fas fa-carrot'></i> Productos </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='#'><i class='fas fa-truck'></i> ADMINISTRACIÓN DE PEDIDOS <span class='fas fa-angle-double-right'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a  href='Cliente/index.php'> <i class='fas fa-user-cog'></i> Administración de Clientes</a>
                                </li>
                                <li>
                                    <a href='Pedido/index.php'> <i class='fas fa-table'></i> Gestión de Pedidos </a>
                                </li>
                                <li>
                                    <a href='Imprimir/index.php'> <i class='fas fa-print'></i> Imprimir Pedidos</a>
                                </li>
                                <li>
                                    <a href='Reporte/index.php'> <i class='fas fa-file-download'></i> Reporte de Entrega Diaria</a>
                                </li>
                            </ul>
                        </li>";
                        }
                        if (isset($_SESSION['Usuario']) && !empty($_SESSION['Usuario']) && $_SESSION["Perfil"] == 'Cliente') {
                            echo "
 
                        <li>
                            <a onclick=irPaginaPrincipal('mispedidos.php')><i class='fas fa-clipboard-list'></i></i> MIS PEDIDOS</a>
                        </li>";
                        }
                        if (!isset($_SESSION['Usuario']) && empty($_SESSION['Usuario'])) {
                            echo "
                        <li>
                            <a onclick=href='login.php'><i class='fas fa-exchange-alt'></i> INGRESAR</a>
                        </li>
                        <li>
                            <a onclick=irPaginaPrincipal('registrocliente.php')><i class='fa fa-sign-out-alt'></i> REGISTRARSE</a>
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
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Acciones para pedidos -->
    <script src="js/Acciones/acciones.js?v=7"></script>
    <script src="Tables/jquery.dataTables.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sb-admin-2.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>
    <!-- Alertas -->


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
</body>

</html>