<?php
// Initialize the session 
session_start();

if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (
    !isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) ||
    !in_array($_SESSION['Perfil'], ['medico', 'admin', "secretaria"])
) {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$tratamiento = $observaciones = "";

// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $observaciones = $_POST["observaciones"];
    $tratamiento = $_POST["tratamiento"];

    $sql = "UPDATE turnos SET observaciones = ?, tratamiento_id = ? WHERE id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $p1, $p2, $param_id);

        // Set parameters
        $p1 = $observaciones;
        $p2 = $tratamiento;

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
    // Check existence of id parameter before processing further
    if (isset($_GET["id"])) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM turnos WHERE id = ? ";
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
                    $observaciones = $row["observaciones"];
                    $tratamiento = $row["tratamiento_id"];
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilodatos.css">
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
                        <h2>Actualizar Observaciones y/o Tratamiento</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <label>Tratamiento</label>
                        <select required name="tratamiento" class="form-control">
                            <?php
                            $result = $link->query("select * from tratamientos where baja='False'");
                            foreach ($result as $row) {
                                $sel = ($row["id"] == $tratamiento?" selected":"");
                                echo "<option ".$sel." value='" . $row['id'] . "'>" . $row["codigo"] . " - " . $row["denominacion"] . "</option>";
                            }
                            ?>
                        </select>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea class="form-control" name="observaciones" maxlength="300" rows="3"><?=$observaciones?></textarea>
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