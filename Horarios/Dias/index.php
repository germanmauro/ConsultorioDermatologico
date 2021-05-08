<?php
// Initialize the session
session_start();

// Include config file
require_once '../../config.php';
require_once '../../Clases/medico.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil'])) {
    header("location: ../../index.php");
    exit;
}
$medico = "";
$dias = [
    0 => "Domingo",
    1 => "Lunes",
    2 => "Martes",
    3 => "Miércoles",
    4 => "Jueves",
    5 => "Viernes",
    6 => "Sábado",
];
if (isset($_GET["id"])) {
    $medico = new Medico($_GET["id"]);
    $_SESSION["Medico"] = serialize($medico);
} else {
    $medico = unserialize($_SESSION["Medico"]);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Médicos</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/estiloprincipal.css">
    <script src="../../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../../Tables/jquery.dataTables.css">
    <script src="../../Tables/jquery.dataTables.js"></script>
    <script src=" ../../js/bootstrap.min.js"> </script>
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
                        <h2 class="pull-left">Días y horarios de <?= $medico->nombre . " " . $medico->apellido ?></h2>
                        <a href="create.php" class="btn btn-success pull-right">Registrar Día</a>
                    </div>

                    <br>
                    <?php


                    $sql = "SELECT *
                     FROM dias
                      where medico_id =" . $medico->id . " 
                    order by dia";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Día</th>";
                            echo "<th>Desde</th>";
                            echo "<th>Hasta</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $dias[$row["dia"]]. "</td>";
                                echo "<td>" . $row['horadesde'] . "</td>";
                                echo "<td>" . $row['horahasta'] . "</td>";


                                echo "<td>";
                                echo "<a href='delete.php?id=" . $row['id'] . "' title='Eliminar día' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";

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