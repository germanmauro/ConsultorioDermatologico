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
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) ||
    !in_array($_SESSION['Perfil'], ['medico', 'admin'])) {
    header("location: ../index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Proveedores</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
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
                        <h2 class="pull-left">Listado de proveedores</h2>
                        <a href="create.php" class="btn btn-success pull-right">Registrar proveedor</a>
                    </div>

                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql = "SELECT *
                     FROM proveedores
                      where baja='False' 
                    order by empresa";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display tablagrande'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Empresa</th>";
                            echo "<th>Dirección</th>";
                            echo "<th>Teléfono</th>";
                            echo "<th>E-mail</th>";
                            echo "<th>Datos Bancarios</th>";
                            echo "<th>Contacto 1</th>";
                            echo "<th>Teléfono 1</th>";
                            echo "<th>E-mail 2</th>";
                            echo "<th>Contacto 2</th>";
                            echo "<th>Teléfono 2</th>";
                            echo "<th>E-mail 1</th>";
                            echo "<th>Comentarios</th>";

                            echo "<th>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['empresa'] . "</td>";
                                echo "<td>" . $row['direccion'] . "</td>";
                                echo "<td>" . $row['telefono'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . str_replace("\n","<br>",$row['datosbancarios']) . "</td>";
                                echo "<td>" . $row['contacto1'] . "</td>";
                                echo "<td>" . $row['telefono1'] . "</td>";
                                echo "<td>" . $row['email1'] . "</td>";
                                echo "<td>" . $row['contacto2'] . "</td>";
                                echo "<td>" . $row['telefono2'] . "</td>";
                                echo "<td>" . $row['email2'] . "</td>";
                                echo "<td>" . $row['comentarios'] . "</td>";
                                
                                echo "<td>";
                                echo "<a href='update.php?id=" . $row['id'] . "' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                echo "<a href='delete.php?id=" . $row['id'] . "' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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