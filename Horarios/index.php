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
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil'])) {
    header("location: ../index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de turnos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
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
                        <h2 class="pull-left">Gestión de horarios de médicos</h2>
                    </div>

                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql = "SELECT *
                     FROM usuarios
                      where (perfil = 'medico' or perfil='submedico') and baja='False' 
                    order by apellido";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Apellido</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>Especialidad</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['apellido'] . "</td>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['especialidad'] . "</td>";
                           

                                echo "<td>";
                                echo "<a href='Dias/index.php?id=" . $row['id'] . "' title='Días y horarios' data-toggle='tooltip'><span class='glyphicon glyphicon-calendar'></span></a>";
                                echo "<a href='Bloqueos/index.php?id=" . $row['id'] . "' title='Bloqueos totales' data-toggle='tooltip'><span class='glyphicon glyphicon-remove'></span></a>";
                                echo "<a href='BloqueosParciales/index.php?id=" . $row['id'] . "' title='Bloqueos parciales' data-toggle='tooltip'><span class='glyphicon glyphicon-minus-sign'></span></a>";
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