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
if (
    !isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) ||
    !in_array($_SESSION['Perfil'], ['medico', 'secretaria', 'admin'])
) {
    header("location: ../index.php");
    exit;
}
$nacimientodesde = $nacimientohasta = $altadesde = $altahasta = $referido = $localidad = "";
if(isset($_POST["referido"]))
{
    $referido = $_POST["referido"];
    $localidad = $_POST["localidad"];
    $nacimientodesde = $_POST["nacimientodesde"];
    $nacimientohasta = $_POST["nacimientohasta"];
    $altadesde = $_POST["altadesde"];
    $altahasta = $_POST["altahasta"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Pacientes</title>
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
                        <h2 class="pull-left">Listado de pacientes</h2>
                        <a href="create.php" class="btn btn-success pull-right">Registrar Paciente</a>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Alta desde</label>
                                <input type="date" name="altadesde" class="form-control" value="<?= $altadesde ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Alta hasta</label>
                                <input type="date" name="altahasta" class="form-control" value="<?= $altahasta ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Localidad</label>
                                <input type="text" name="localidad" class="form-control" value="<?= $localidad ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Nacimiento desde</label>
                                <input type="date" name="nacimientodesde" class="form-control" value="<?= $nacimientodesde ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Nacimiento hasta</label>
                                <input type="date" name="nacimientohasta" class="form-control" value="<?= $nacimientohasta ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label>Referido</label>
                                <input type="text" name="referido" class="form-control" value="<?= $referido ?>">
                            </div>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Filtrar">
                    </form>
                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql = "SELECT pacientes.id as id,pacientes.codigo,pacientes.nombre,pacientes.apellido,pacientes.dni,
                    obrassociales.nombre as obrasocial,pacientes.carnet, pacientes.telefono,
                    pacientes.email,pacientes.direccion, pacientes.localidad, 
                    DATE_FORMAT(pacientes.fechanacimiento,'%d/%m/%Y') as fechanacimiento,
                    DATE_FORMAT(pacientes.alta,'%d/%m/%Y') as alta,
                    pacientes.profesion, pacientes.referido
                     FROM pacientes
                     join obrassociales on obrassociales.id = pacientes.obrasocial_id
                      where pacientes.baja='False'
                      AND (pacientes.localidad  like '%" . $localidad . "%')
                      AND (pacientes.referido  like '%" . $referido . "%')";
                      if($altadesde!="")
                      {
                        $sql.= "AND (pacientes.alta  between '" . $altadesde . "' and '" . $altahasta . "')";
                      }
                      if($nacimientodesde!="")
                      {
                        $sql.= "AND (pacientes.fechanacimiento  between '" . $nacimientodesde . "' and '" . $nacimientohasta . "')";
                      }
                      
                    $sql.=" order by apellido";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display tablagrande'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>HC</th>";
                            echo "<th>Código</th>";
                            echo "<th>Apellido</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>DNI</th>";
                            echo "<th>Obra Social</th>";
                            echo "<th>Carnet</th>";
                            echo "<th>Teléfono </th>";
                            echo "<th>E-mail</th>";
                            echo "<th>Dirección</th>";
                            echo "<th>Localidad</th>";
                            echo "<th>Fecha nacimiento</th>";
                            echo "<th>Profesion</th>";
                            echo "<th>Referido</th>";
                            echo "<th>Alta</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>";
                                echo "<a href='historiaclinica.php?id=" . $row['id'] . "' title='Ver Historia Clínica' data-toggle='tooltip'><span class='glyphicon glyphicon-file'></span></a>";
                                echo "</td>";
                                echo "<td>" . $row['codigo'] . "</td>";
                                echo "<td>" . $row['apellido'] . "</td>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['dni'] . "</td>";
                                echo "<td>" . $row['obrasocial'] . "</td>";
                                echo "<td>" . $row['carnet'] . "</td>";
                                echo "<td>" . $row['telefono'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['direccion'] . "</td>";
                                echo "<td>" . $row['localidad'] . "</td>";
                                echo "<td>" . $row['fechanacimiento'] . "</td>";
                                echo "<td>" . $row['profesion'] . "</td>";
                                echo "<td>" . $row['referido'] . "</td>";
                                echo "<td>" . $row['alta'] . "</td>";

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