<?php
// Initialize the session 
session_start();

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
$nombre = $apellido = $dni = $especialidad = $matriculanacional = $matriculaprovincial =
    $user = $pass = "";

// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $especialidad = $_POST["especialidad"];
    $matriculanacional = $_POST["matriculanacional"];
    $matriculaprovincial = $_POST["matriculaprovincial"];
    $user = $_POST["user"];
    $pass = $_POST["pass"];

    $sql = "UPDATE usuarios SET nombre=?, apellido=?, dni=?, especialidad = ?, matriculanacional = ?,
     matriculaprovincial = ?, user = ?  WHERE id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $param_id);

        // Set parameters
        $p1 = $nombre;
        $p2 = $apellido;
        $p3 = $dni;
        $p4 = $especialidad;
        $p5 = $matriculanacional;
        $p6 = $matriculaprovincial;
        $p7 = $user;

        $param_id = $id;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            if ($pass != "") {
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                if ($link->query("update usuarios set pass='". $pass."' where id=" .$id)) {
                    header("location:index.php");
                } else {
                    echo "Ocurrio un error.";
                }
            }
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
        $sql = "SELECT * FROM usuarios WHERE id = ? ";
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
                    $nombre = $row["nombre"];
                    $apellido = $row["apellido"];
                    $dni = $row["dni"];
                    $especialidad = $row["especialidad"];
                    $matriculanacional = $row["matriculanacional"];
                    $matriculaprovincial = $row["matriculaprovincial"];
                    $user = $row["user"];
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
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input class="form-control" required id="nombre" name="nombre" maxlength="20" placeholder="Nombre" value='<?php echo $nombre; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" required class="form-control" id="apellido" name="apellido" maxlength="20" placeholder="Apellido" value='<?php echo $apellido; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Especialidad</label>
                                <input type="text" required class="form-control" id="especialidad" name="especialidad" maxlength="50" placeholder="Especialidad" value='<?php echo $especialidad; ?>'>
                            </div>
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" required class="form-control" id="dni" name="dni" minlength="6" maxlength="15" value='<?php echo $dni; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Matricula Nacional</label>
                                <input type="text" class="form-control" id="matriculanacional" name="matriculanacional" minlength="6" maxlength="15" value='<?php echo $matriculanacional; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Matricula Provincial</label>
                                <input type="text" class="form-control" id="matriculaprovincial" name="matriculaprovincial" minlength="6" maxlength="15" value='<?php echo $matriculaprovincial; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Usuario</label>
                                <input type="text" class="form-control" id="user" name="user" minlength="6" maxlength="20" value='<?php echo $user; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Contraseña (Vacío para matener la misma)</label>
                                <input class="form-control" id="pass" name="pass" minlength="8" maxlength="30" placeholder="Contraseña">
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