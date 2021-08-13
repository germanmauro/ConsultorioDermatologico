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
$desde = $hasta = $medico = $tratamiento = $producto = "";
if (isset($_POST["desde"])) {
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $medico = $_POST["medico"];
    $producto = $_POST["producto"];
    $tratamiento = $_POST["tratamiento"];
} else {
    $hoy = new DateTime();
    $hoy = $hoy->format('Y-m-d');
    $desde = $hoy;
    $hasta = $hoy;
    $medico = "0";
    $producto = "0";
    $tratamiento = "0";
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
                "lengthMenu": [10, 25, 50, 75, 100, 1000, 2000],
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
                        <h2 class="pull-left">Planilla Diaria</h2>
                    </div>
                    <div class="col-md-12">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label>Desde</label>
                                    <input type="date" name="desde" class="form-control" value="<?= $desde ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label>Hasta</label>
                                    <input type="date" name="hasta" class="form-control" value="<?= $hasta ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Médico</label>
                                    <select name="medico" class='form-control'>
                                        <option value="0">Todos</option>
                                        <?php
                                        $sql1 = "SELECT * from usuarios
                                        where (perfil='medico' or perfil='submedico')
                                        and baja='False'
                                            order by apellido";
                                        if ($result1 = mysqli_query($link, $sql1)) {
                                            if (mysqli_num_rows($result1) > 0) {
                                                while ($row = mysqli_fetch_array($result1)) {
                                                    $sel = "";
                                                    if ($row["id"] == $medico) {
                                                        $sel = "selected";
                                                    }
                                                    echo "<option " . $sel . " value='" . $row["id"] . "'>" . $row["apellido"] . ", " . $row["nombre"] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Producto</label>
                                    <select name="producto" class='form-control'>
                                        <option <?= ($producto == '0' ? 'selected ' : '') ?>value="0">Todos</option>
                                        <option <?= ($producto == '-1' ? 'selected ' : '') ?>value="-1">Ninguno</option>
                                        <?php
                                        $sql1 = "SELECT * from productos
                                        where  baja='False'
                                            order by codigo";
                                        if ($result1 = mysqli_query($link, $sql1)) {
                                            if (mysqli_num_rows($result1) > 0) {
                                                while ($row = mysqli_fetch_array($result1)) {
                                                    $sel = "";
                                                    if ($row["id"] == $producto) {
                                                        $sel = "selected";
                                                    }
                                                    echo "<option " . $sel . " value='" . $row["id"] . "'>" . $row["codigo"] . " - " . $row["denominacion"] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tratamiento</label>
                                    <select name="tratamiento" class='form-control'>
                                        <option <?= ($tratamiento == '0' ? 'selected ' : '') ?>value="0">Todos</option>
                                        <option <?= ($tratamiento == '-1' ? 'selected ' : '') ?>value="-1">Ninguno</option>
                                        <?php
                                        $sql1 = "SELECT * from tratamientos
                                        where  baja='False'
                                            order by codigo";
                                        if ($result1 = mysqli_query($link, $sql1)) {
                                            if (mysqli_num_rows($result1) > 0) {

                                                while ($row = mysqli_fetch_array($result1)) {
                                                    $sel = "";
                                                    if ($row["id"] == $tratamiento) {
                                                        $sel = "selected";
                                                    }
                                                    echo "<option " . $sel . " value='" . $row["id"] . "'>" . $row["codigo"] . " - " . $row["denominacion"] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><input type="submit" class="btn btn-primary" value="Filtrar"></div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';

                    $sql =
                    "SELECT ventas.id as id,fecha as fech, DATE_FORMAT(ventas.fecha,'%d/%m/%Y') as fecha,factura,
                    pacientes.codigo as pacientecodigo, pacientes.nombre as pacientenombre,
                    pacientes.apellido as pacienteapellido, pacientes.dni as dni,
                    CONCAT(usuarios.nombre,' ',usuarios.apellido) as medico,
                    CONCAT(vendedor.nombre,' ',vendedor.apellido) as vendedor,
                    tratamientos.codigo as itemcodigo, tratamientos.denominacion as itemdenominacion,
                    cantidad,ventastratamientos.total,formaspago.nombre as formapago,comision,observaciones
                     FROM ventas
                     join ventastratamientos on ventastratamientos.venta_id = ventas.id
                     join pacientes on pacientes.id = ventas.paciente_id
                     join tratamientos on tratamientos.id = ventastratamientos.tratamiento_id
                     join formaspago on formaspago.id = ventas.formapago_id
                     join usuarios on usuarios.id = ventastratamientos.medico_id
                     join usuarios as vendedor on vendedor.id = ventas.usuario_id
                     WHERE ventas.fecha between '" . $desde . "' and '" . $hasta . "'
                     AND ('0' = '" . $medico . "' OR ventastratamientos.medico_id=" . $medico . ")
                     AND ('0' = '" . $tratamiento . "' OR ventastratamientos.tratamiento_id=" . $tratamiento . ")
                      UNION

                      SELECT ventas.id as Id,fecha as fech,DATE_FORMAT(ventas.fecha,'%d/%m/%Y') as fecha,factura,
                    pacientes.codigo as pacientecodigo, pacientes.nombre as pacientenombre,
                    pacientes.apellido as pacienteapellido, pacientes.dni as dni,
                    '' as medico,
                    CONCAT(vendedor.nombre,' ',vendedor.apellido) as vendedor,
                    productos.codigo as itemcodigo, productos.denominacion as itemdenominacion,
                    cantidad,ventasproductos.total,formaspago.nombre as formapago,0 as comision,observaciones
                     FROM ventas
                     join ventasproductos on ventasproductos.venta_id = ventas.id
                     join pacientes on pacientes.id = ventas.paciente_id
                     join productos on productos.id = ventasproductos.producto_id
                     join formaspago on formaspago.id = ventas.formapago_id
                     join usuarios as vendedor on vendedor.id = ventas.usuario_id
                     WHERE ventas.fecha between '" . $desde . "' and '" . $hasta . "'
                     AND ('0' = '" . $producto . "' OR ventasproductos.producto_id=" . $producto . ")
                      order by fech desc";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display'>";
                            echo "<thead>";
                            echo "<tr>";


                            echo "<th>Fecha</th>";
                            echo "<th>N°FC</th>";
                            echo "<th>Cód Paciente</th>";
                            echo "<th>Apellido</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>DNI</th>";
                            echo "<th>Vendedor</th>";
                            echo "<th>Código</th>";
                            echo "<th>Tratamiento/Producto</th>";
                            echo "<th>Cantidad</th>";
                            echo "<th>Monto</th>";
                            echo "<th>Forma Pago</th>";
                            echo "<th>Médica</th>";
                            echo "<th>Comisión</th>";
                            echo "<th>Observaciones</th>";
                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['fecha'] . "</td>";
                                echo "<td>" . $row['factura'] . "</td>";
                                echo "<td>" . $row['pacientecodigo'] . "</td>";
                                echo "<td>" . $row['pacienteapellido'] . "</td>";
                                echo "<td>" . $row['pacientenombre'] . "</td>";
                                echo "<td>" . $row['dni'] . "</td>";
                                echo "<td>" . $row['vendedor'] . "</td>";
                                echo "<td>" . $row['itemcodigo'] . "</td>";
                                echo "<td>" . $row['itemdenominacion'] . "</td>";
                                echo "<td>" . $row['cantidad'] . "</td>";
                                echo "<td>" . $row['total'] . "</td>";
                                echo "<td>" . $row['formapago'] . "</td>";
                                echo "<td>" . $row['medico'] . "</td>";
                                echo "<td>" . $row['comision'] . "</td>";
                                echo "<td>" . $row['observaciones'] . "</td>";
                                echo "<td>";
                                echo "<a href='../index.php?venta=" . $row['id'] . "' title='Actualizar Venta' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a>";
                                echo " <a href='update.php?venta=" . $row['id'] . "' title='Modificar Fecha' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
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