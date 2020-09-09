<?php
// Initialize the session
session_start();

// // If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) != 'admin') {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$nombre = $user = $apellido = $dni = "";


// Processing form data when form is submitted
if (isset($_POST["id"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $user = $_POST["user"];
    $dni = $_POST["dni"];

    // Check input errors before inserting in database
    $sql = "UPDATE usuario SET Nombre=?,Apellido=?,DNI=? WHERE User=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssss", $p1, $p2, $p3, $param_id);

        // Set parameters
        $p1 = $nombre;
        $p2 = $apellido;
        $p3 = $dni;

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
        $sql = "SELECT * FROM usuario WHERE User = ?";
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
                    $nombre = $row["Nombre"];
                    $apellido = $row["Apellido"];
                    $dni = $row["DNI"];
                    $user = $row["User"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

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
                        <h2>Actualizar Registro</h2>
                    </div>
                    <p>Ingrese los datos a actualizar.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="apellido" required maxlength=40 class="form-control" value="<?php echo $apellido; ?>">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=40 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label>DNI/CUIT</label>
                            <input type="text" name="dni" maxlength=15 class="form-control" value="<?php echo $dni; ?>">
                        </div>
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="user" name="user" maxlength=40 class="form-control" value="<?php echo $user; ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>