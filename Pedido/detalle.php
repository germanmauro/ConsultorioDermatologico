<?php
// Initialize the session
session_start();

// Include config file
require_once '../config.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../index.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) != 'admin') {
    header("location: ../index.php");
    exit;
}

$id = "";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del pedido</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
    <style type="text/css">
        .wrapper {

            margin: 0 auto;
        }

        .page-header h2 {
            margin-top: 0;
        }

        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tabla').DataTable();
        });
    </script>
</head>


<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <!-- <a href="../index.php" class="btn btn-success">Volver</a> -->
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Detalle del pedido</h2>
                        <a onclick="javascript:window.close()" class="btn btn-success pull-right">Cerrar Detalle</a>
                    </div>

                    <br>

                    <div class='table-responsive'>
                        <table class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Cant</th>
                                    <th>$ Un.</th>
                                    <th>$ Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $link->query("select pedidodetalle.Cantidad as Cantidad,
                                    pedidodetalle.Nombre as Nombre,producto.Imagen,producto.Tipo,
                                    pedidodetalle.Precio as Precio, pedidodetalle.Total as Total
                                    from pedidodetalle
                                    join producto on pedidodetalle.Producto=producto.Id
                                    where pedidodetalle.Pedido=" . $id);
                                while ($row = mysqli_fetch_array($result)) {
                                    $tipo = "";
                                    if ($row["Tipo"] == "Unidad") {
                                        $tipo = "Unidad";
                                    } else {
                                        $tipo = "Kg";
                                    }
                                    echo "<tr>";
                                    echo "<td>";
                                    echo "<div class='img'>" . $row["Nombre"] . "</div>";
                                    echo "<img src='../Producto/" . $row["Imagen"] . "' class='menu-images' />";
                                    echo "<div class='img'>Por " . $tipo . "</div>";
                                    echo "</td>";
                                    echo "<td align='center'>";
                                    echo $row["Cantidad"];
                                    echo "</td>";
                                    echo "<td align='center'>";
                                    echo $row["Precio"];
                                    echo "</td>";
                                    echo "<td align='center'>";
                                    echo $row["Total"];
                                    echo "</td>";

                                    echo "</tr>";
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

</body>

</html>