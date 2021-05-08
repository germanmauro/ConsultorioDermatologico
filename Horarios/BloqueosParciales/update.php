<?php
// Initialize the session 
session_start();

if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil'])) {
    header("location: ../index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../../config.php';

// Define variables and initialize with empty values
$dia = $desde = $hasta = $motivo = "";

// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $dia = $_POST["dia"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $motivo = $_POST["motivo"];
    $horadesde = new DateTime('2020-01-01 ' . $desde);
    $horahasta = new DateTime('2020-01-01 ' . $hasta);
    if ($horadesde < $horahasta) {
    $sql = "UPDATE bloqueosparciales SET dia = ?, horadesde = ?, horahasta = ?, motivo = ? WHERE id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssss", $p1, $p2, $p3, $p4, $param_id);

        // Set parameters
        $p1 = $dia;
        $p2 = $desde;
        $p3 = $hasta;
        $p4 = $motivo;

        $param_id = $id;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else {
            echo "Ocurrió un error.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
    } else {
        echo "
        <div class='alert alert-warning' role='alert'>
        La hora desde debe ser mayor a la hora hasta
        </div>  ";
    }
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"])) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM bloqueosparciales WHERE id = ? ";
        //echo $sql;
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $dia = $row["dia"];
                    $desde = $row["horadesde"];
                    $hasta = $row["horahasta"];
                    $motivo = $row["motivo"];
                    $horadesde = (new DateTime('2020-01-01 ' . $desde))->format('H:i');
                    $horahasta = (new DateTime('2020-01-01 ' . $hasta))->format('H:i');
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/estilodatos.css">
    <style type="text/css">
        .wrapper {

            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Ingrese los datos a actualizar.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Día</label>
                                <input required type="date" name="dia" class="form-control" value="<?= $dia ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Desde</label>
                                <select required name="desde" class="form-control">
                                    <?php
                                    for ($i = 0; $i < 24; $i++) {
                                        echo "<option ".($i.":00"==$desde?'selected':"")." value='" . $i . ":00'>" . $i . ":00</option>";
                                        echo "<option " . ($i . ":30" == $desde ? 'selected' : "") . " value='" . $i . ":30'>" . $i . ":30</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Hasta</label>
                                <select required name="hasta" class="form-control">
                                    <?php
                                    echo "<option " . ("0:30" == $hasta ? 'selected' : "") . "value='0:30'>0:30</option>";
                                    for ($i = 1; $i < 24; $i++) {
                                        echo "<option " . ($i . ":00" == $hasta ? 'selected' : "") . " value='" . $i . ":00'>" . $i . ":00</option>";
                                        echo "<option " . ($i . ":30" == $hasta ? 'selected' : "") . " value='" . $i . ":30'>" . $i . ":30</option>";
                                    }
                                    echo "<option value='24:00'>24:00</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo</label>
                                <input type="text" name="motivo" class="form-control" value="<?= $motivo ?>">
                            </div>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" name="actualizar" class="btn btn-primary" value="Actualizar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>