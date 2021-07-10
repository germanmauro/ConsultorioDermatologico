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
$hoy = new DateTime();
$hoy = $hoy->format("Y-m-d")
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agenda de turnos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
    <!-- alertas -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
                        <h2 class="pull-left">Agenda de turnos</h2>
                    </div>
                    <form target="_blank" action="listadopdf.php" method="post">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Médico</label>
                                <select name="medico" class='form-control'>
                                    <?php 
                                    if($_SESSION['Perfil']!="submedico")
                                    {
                                        echo "<option value='0'>Todos</option>";
                                    }
                                    ?>
                                    
                                    <?php
                                    $sql1 = "SELECT * from usuarios 
                                    where (perfil='medico' or perfil='submedico')
                                    and baja='False'
                                        order by apellido";

                                    if ($result1 = mysqli_query($link, $sql1)) {
                                        if (mysqli_num_rows($result1) > 0) {
                                            $sel = "";
                                            while ($row = mysqli_fetch_array($result1)) {
                                                if($_SESSION['Perfil'] != "submedico"|| $_SESSION['Id'] == $row["id"])
                                                {
                                                    echo "<option value='" . $row["id"] . "'>" . $row["apellido"] . ", " . $row["nombre"] . "</option>";
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Fecha desde</label>
                                <input type="date" name="desde" required class="form-control" value="<?= $hoy ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Fecha hasta</label>
                                <input type="date" name="hasta" required class="form-control" value="<?= $hoy ?>">
                            </div>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Imprimir agenda de turnos">
                    </form>
                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql = "SELECT turnos.id as id, DATE_FORMAT(fecha,'%d/%m/%Y %H:%i') as fecha,observaciones,
                     CONCAT(pacientes.apellido,', ',pacientes.nombre) as paciente,
                     CONCAT(tratamientos.codigo,' - ',tratamientos.denominacion) as tratamiento,duracion,
                     CONCAT(usuarios.apellido,', ',usuarios.nombre) as medico
                     FROM turnos
                     join usuarios on usuarios.id = turnos.medico_id
                     join pacientes on pacientes.id = turnos.paciente_id
                     left join tratamientos on tratamientos.id = turnos.tratamiento_id";
                    if ($_SESSION['Perfil'] == "submedico") {
                        $sql .= " where turnos.medico_id=" . $_SESSION["Id"];
                    }
                    $sql .= " order by turnos.fecha desc";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Fecha</th>";
                            echo "<th>Médico</th>";
                            echo "<th>Paciente</th>";
                            // echo "<th>Tratamiento</th>";
                            echo "<th>Duración</th>";
                            echo "<th>Observaciones</th>";
                            if ($_SESSION['Perfil'] != "submedico") {
                            echo "<th></th>";
                            }
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['fecha'] . "</td>";
                                echo "<td>" . $row['medico'] . "</td>";
                                echo "<td>" . $row['paciente'] . "</td>";
                                // echo "<td>" . $row['tratamiento'] . "</td>";
                                echo "<td>" . $row['duracion'] . "</td>";
                                echo "<td>" . $row['observaciones'] . "</td>";
                                if ($_SESSION['Perfil'] != "submedico") {
                                echo "<td>
                                        <a onclick=eliminarTurno(" . $row['id'] . ") title='Cancelar Turno' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>
                                     <a href='update.php?id=" . $row['id'] . "' title='Modificar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>
                                        </td>";
                                }
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
<script>
    //Borrar Producto del pedido
    function eliminarTurno(id) {
        swal("¿Desea cancelar el turno?", {
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
                        $.ajax({
                            url: '../Accion/eliminarTurno.php',
                            data: {
                                id: id
                            },
                            type: 'post',
                            cache: false,
                            success: function(r) {
                                if (r != "") {
                                    alert(r);
                                    //return r;
                                } else {
                                    location.reload();
                                }
                            }
                        });
                        break;

                }
            });
    }
</script>

</html>