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
$nombre = $ubicacion = $cantidadpedidos = $lunes = $martes = $miercoles = $jueves = $viernes = $sabado = $domingo = $id = "";

// Processing form data when form is submitted
if (isset($_POST["id"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $ubicacion = $_POST["ubicacion"];
    $lunes = $_POST["lunes"];
    $martes = $_POST["martes"];
    $miercoles = $_POST["miercoles"];
    $jueves = $_POST["jueves"];
    $viernes = $_POST["viernes"];
    $sabado = $_POST["sabado"];
    $domingo = $_POST["domingo"];
    $cantidadpedidos = $_POST["cantidadpedidos"];

    // Check input errors before inserting in database
    $sql = "UPDATE zona SET Nombre=?,Ubicacion = ?,Lunes = ?,Martes = ?,Miercoles = ?,Jueves = ?,Viernes = ?,
    Sabado = ?,Domingo = ?, CantidadPedidos = ? WHERE Id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $param_id);

        // Set parameters
        $p1 = $nombre;
        $p2 = $ubicacion;
        $p3 = $lunes;
        $p4 = $martes;
        $p5 = $miercoles;
        $p6 = $jueves;
        $p7 = $viernes;
        $p8 = $sabado;
        $p9 = $domingo;
        $p10 = $cantidadpedidos;

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
        $sql = "SELECT * FROM zona WHERE Id = ?";
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
                    $ubicacion = $row["Ubicacion"];
                    $lunes = $row["Lunes"];
                    $martes = $row["Martes"];
                    $miercoles = $row["Miercoles"];
                    $jueves = $row["Jueves"];
                    $viernes = $row["Viernes"];
                    $sabado = $row["Sabado"];
                    $domingo = $row["Domingo"];
                    $cantidadpedidos = $row["CantidadPedidos"];
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
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=30 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ubicación</label>
                            <input type="text" name="ubicacion" required maxlength=30 class="form-control" value="<?php echo $ubicacion; ?>">
                        </div>
                        <div class="form-group">
                            <label>Pedidos por Día</label>
                            <input type="number" name="cantidadpedidos" required min=0 max=10000 class="form-control" value="<?php echo $cantidadpedidos; ?>">
                        </div>
                        <h2>Días Habilitados</h2>
                        <div class="form-group">
                            <table width="100%">
                                <tr>
                                    <td width="25%">
                                        <label>Lunes</label>
                                        <select id="lunes" name="lunes" class="form-control" required>
                                            <option <?php echo ($lunes == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($lunes == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Martes</label>
                                        <select id="martes" name="martes" class="form-control" required>
                                            <option <?php echo ($martes == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($martes == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Miercoles</label>
                                        <select id="miercoles" name="miercoles" class="form-control" required>
                                            <option <?php echo ($miercoles == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($miercoles == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Jueves</label>
                                        <select id="jueves" name="jueves" class="form-control" required>
                                            <option <?php echo ($jueves == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($jueves == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                <tr>
                                <tr>
                                    <td width="25%">
                                        <label>Viernes</label>
                                        <select id="viernes" name="viernes" class="form-control" required>
                                            <option <?php echo ($viernes == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($viernes == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Sábado</label>
                                        <select id="sabado" name="sabado" class="form-control" required>
                                            <option <?php echo ($sabado == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($sabado == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Domingo</label>
                                        <select id="domingo" name="domingo" class="form-control" required>
                                            <option <?php echo ($domingo == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                            <option <?php echo ($domingo == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                                        </select>
                                    </td>
                                <tr>
                            </table>
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