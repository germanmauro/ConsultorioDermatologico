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
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
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
            $('#tabla').DataTable({
                "order" : []
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
                        <h2 class="pull-left">Producto</h2>
                        <a href="create.php" class="btn btn-success pull-right">Registrar Producto</a>
                    </div>

                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql = "SELECT producto.Codigo as Codigo,producto.Nombre as Nombre,producto.Id as Id,
                    categoria.Nombre as Categoria,
                     producto.Imagen as Imagen, producto.Precio as Precio,
                     producto.Stock as Stock, producto.Tipo as Tipo, producto.Habilitado as Habilitado
                     FROM producto
                     JOIN categoria on categoria.Id = producto.Categoria
                      where producto.Baja='False' 
                    order by Nombre";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Código</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>Categoría</th>";
                            echo "<th>Tipo</th>";
                            echo "<th>Stock</th>";
                            echo "<th>Habilitado</th>";
                            echo "<th>Precio</th>";
                            echo "<th>Imágen</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['Codigo'] . "</td>";
                                echo "<td>" . $row['Nombre'] . "</td>";
                                echo "<td>" . $row['Categoria'] . "</td>";
                                echo "<td>" . $row['Tipo'] . "</td>";
                                echo "<td>" . $row['Stock'] . "</td>";
                                echo "<td>" . $row['Habilitado'] . "</td>";
                                echo "<td>" . $row['Precio'] . "</td>";
                                
                                echo "<td> <img src='" . $row['Imagen'] . "' width=150px heigth=150px </td>";
                                echo "<td>";
                                echo "<a href='update.php?id=" . $row['Id'] . "' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                echo "<a href='delete.php?id=" . $row['Id'] . "' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
</body>

</html>