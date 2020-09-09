<?php
// Initialize the session
session_start();

// Include config file
require_once '../config.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) != 'admin') {
    header("location: ../index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
    <!-- alertas -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
            $('#tabla').DataTable({
                "order": []
            });
        });
    </script>
</head>


<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <a href="../index.php" class="btn btn-success">Volver</a>
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Pedidos</h2>
                        <!-- <a href="create.php" class="btn btn-success pull-right">Registrar Personal</a> -->
                    </div>

                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';


                    $sql = "SELECT pedido.Id as Id, CONCAT(usuario.Nombre,' ',usuario.Apellido) as Cliente,pedido.Direccion as Direccion,
                    CONCAT(zona.Nombre,' - ',zona.Ubicacion) as Zona, pedido.Telefono as Telefono,
                    pedido.Total as Total,pedido.Fecha as Fecha,DATE_FORMAT(pedido.Fecha,'%d/%m/%y') as Fecha,
                    DATE_FORMAT(pedido.FechaEntrega,'%d/%m/%y') as FechaEntrega,pedido.Estado, pedido.FormaPago,Comentarios
                    FROM pedido
                    join usuario on usuario.User = pedido.Cliente
                    join zona on zona.Id = pedido.Zona
                    where 
                    Perfil = 'Cliente' 
                    order by pedido.Id desc";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Fecha</th>";
                            echo "<th>Entrega</th>";
                            echo "<th>Cliente</th>";
                            echo "<th>Dirección</th>";
                            echo "<th>Zona</th>";
                            echo "<th>Teléfono</th>";
                            echo "<th>Total</th>";
                            echo "<th>Forma Pago</th>";
                            echo "<th>Comentarios</th>";
                            echo "<th>Estado</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['Fecha'] . "</td>";
                                echo "<td>" . $row['FechaEntrega'] . "</td>";
                                echo "<td>" . $row['Cliente'] . "</td>";
                                echo "<td>" . $row['Direccion'] . "</td>";
                                echo "<td>" . $row['Zona'] . "</td>";
                                echo "<td>" . $row['Telefono'] . "</td>";
                                echo "<td>" . $row['Total'] . "</td>";
                                echo "<td>" . $row['FormaPago'] . "</td>";
                                echo "<td>" . $row['Comentarios'] . "</td>";
                                echo "<td>" . $row['Estado'] . "</td>";

                                echo "<td>";
                                if ($row['Estado'] == 'Cancelado') {
                                    echo "<a onclick=habilitar('" . $row['Id'] . "') title='Habilitar Pedido' data-toggle='tooltip'><span class='glyphicon glyphicon-ok'></span></a>";
                                } else {
                                    echo "<a onclick=deshabilitar('" . $row['Id'] . "') title='Cancelar Pedido' data-toggle='tooltip'><span class='glyphicon glyphicon-remove'></span></a>";
                                }
                                echo "<a target='_blank' href='detalle.php?id=" . $row['Id'] . "' title='Detalle del pedido' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo "<p class='lead'><em>No hay registros.</em></p>";
                        }
                    } else {
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>

    </div>
    <script>
        function habilitar(id) {
            $.ajax({
                url: 'habilitar.php?Id=' + id,
                //data: cat,
                cache: false,
                success: function(r) {
                    if (r != "") {
                        alert(r);
                        //return r;
                    } else {
                        swal("¿Desea cambiar el estado del pedido?", {
                                icon: "warning",
                                buttons: {


                                    catch: {
                                        text: "SI",
                                        value: "catch",
                                    },
                                    cancel: "NO",
                                },
                            })
                            .then((value) => {
                                switch (value) {

                                    case "catch":
                                        location.reload();
                                        break;

                                }
                            });
                    }
                }
            });


        }

        function deshabilitar(id) {
            $.ajax({
                url: 'deshabilitar.php?Id=' + id,
                //data: cat,
                cache: false,
                success: function(r) {
                    if (r != "") {
                        alert(r);
                        //return r;
                    } else {

                        swal("¿Desea cambiar el estado del pedido?", {
                                icon: "warning",
                                buttons: {


                                    catch: {
                                        text: "SI",
                                        value: "catch",
                                    },
                                    cancel: "NO",
                                },
                            })
                            .then((value) => {
                                switch (value) {

                                    case "catch":
                                        location.reload();
                                        break;

                                }
                            });

                    }
                }
            });

        }
    </script>
</body>

</html>