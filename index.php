<?php

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
    <meta name="description" content="Verdulería Online en Zona Sur - Quilmes, Bernal, Hudson">
    <meta name="author" content="mygsystems">

    <title>Verdulería Super Verde</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css?v=8" rel="stylesheet">
    <link href="css/sb-admin-2.css?v=18" rel="stylesheet">
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
                        <li><a href="index.php"><i class='fas fa-home'></i> Inicio</a>
                        </li>
                        <?php
                        if (isset($_SESSION['Usuario']) || !empty($_SESSION['Usuario'])) {
                            if ($_SESSION["Perfil"] == "Cliente") {
                                echo "<li><a onclick=paginaPrincipal('pedidodetalle.php')><i class='fas fa-shopping-basket'></i> Ver Canasta</a>
                            </li>";
                            }
                            echo "
                            <li><a onclick=paginaPrincipal('cambioNombre.php')><i class='fas fa-exchange-alt'></i> Modificar Datos</a>
                            </li>
                            <li><a onclick=desloguear()><i class='fa fa-sign-out-alt'></i> Logout</a>
                            </li>";
                        } else {
                            echo "
                            <li><a onclick=href='login.php'><i class='fas fa-exchange-alt'></i> Ingresar</a>
                            </li>
                            <li><a onclick=paginaPrincipal('producto.php')><i class='fas fa-carrot'></i> Ver Catálogo</a>
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
                        <li>
                            <a href='index.php'><i class='fas fa-home'></i> INICIO</a>
                        </li>
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
                            <a onclick=irPaginaPrincipal('pedido.php')><i class='fas fa-shopping-cart'></i> COMPRAR</a>
                        </li>
                        <li>
                            <a onclick=irPaginaPrincipal('pedidodetalle.php')><i class='fas fa-shopping-basket'></i> VER CANASTA</a>
                        </li>
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
                            <a onclick=irPaginaPrincipal('productos.php')><i class='fas fa-carrot'></i> VER CATÁLOGO</a>
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

        <!-- Acá se cargan todas la páginas -->
        <div id="page-wrapper">
            <div class='row'>
                <div class='col-lg-12'>
                    <!-- /.panel-heading -->
                    <div class='panel panel-default inicio'>
                        <div class='panel-body'>
                            <div class='row'>
                                <div class='col-lg-12'>
                                    <h1 class="marca">Verdulería Super Verde</h1>
                                    <h2 class="marca-leyenda">Frutas y verduras de primera calidad</h2>
                                    <h3 class="marca">Productos 100% Frescos</h3>
                                    <div class="logo-div">
                                        <img class="logo" src="images/logo.png" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="info-envio">
                                    <?php
                                    $result = $link->query("select Ubicacion 
                                    from zona where Baja='False' order by Ubicacion");
                                    echo "<h2><i class='fas fa-map-marked-alt'></i> Envíos a:</h2>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<i class='fas fa-map-marker-alt'></i> " . $row["Ubicacion"] . "  ";
                                    }

                                    ?>

                                </div>
                            </div>
                            <div class="row">
                                <div class="info-costo">
                                    <?php
                                    $result = $link->query("select MontoEnvio from configuracion");
                                    $row = mysqli_fetch_array($result);
                                    $envio = $row["MontoEnvio"];
                                    ?>
                                    <h2><i class='fas fa-truck'></i> Envios desde $<?= $envio; ?></h2>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-lg-12'>
                                    <h1 class="marca">Ofertas</h1>
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                        
                                        <?php

                                        $result = $link->query("select producto.Id as Id,producto.Nombre as Nombre,Precio,Imagen,
                                        categoria.Nombre as Categoria, producto.Tipo as Tipo
                                        from producto
                                        join categoria on categoria.Id = producto.Categoria
                                        where categoria.Prioridad = 0
                                        and producto.Baja='False' and Habilitado='Si' order by Nombre");
                                        $cantidad = mysqli_num_rows($result);
                                        
                                        echo "<ol class='carousel-indicators'>";
                                        for ($i=0; $i < $cantidad ; $i++) { 
                                            echo "<li data-target='#myCarousel' data-slide-to='".$i."' ".($i==0?"class='active'":"")."></li>";                              
                                        }
                                        echo "</ol>";
                                        echo "
                                        <div class='carousel-inner'>";
                                        $first = true;
                                        $active = " active";
                                        while ($row = mysqli_fetch_array($result)) {
                                            if ($first) {
                                                $first = false;
                                            } else {
                                                $active = "";
                                            }
                                            $tipo = "";
                                            if ($row["Tipo"] == "Unidad") {
                                                $tipo = "Unidad";
                                            } else {
                                                $tipo = "Kg";
                                            }
                                            echo "<div class='item" . $active . "'>
                                                <img class='inicio-img' src='Producto/" . $row["Imagen"] . "' alt='Los Angeles' style='width:100%;'>
                                                <div class='carousel-caption'>
                                                    <h2>" . $row["Nombre"] . "</h2>
                                                    <h3>$ " . $row["Precio"] . " Por " . $tipo . "</h3>
                                                </div>
                                            </div>";
                                        }
                                        ?>


                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="panel-footer">
        <p class="footer">
            Desarrollado por <a href="https://www.mygsystems.com">M&G Systems.com</a>
        </p>
    </div>
    </div>
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