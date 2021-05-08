<?php
// Include config file
require_once 'config.php';
// Initialize the session
session_start();

if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    echo "<script>
    location.reload();
    </script>";
}

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: index.php");
    exit;
}

//Definición de variables
$nombre = $apellido = $pass = $passrepeat = $dni = $especialidad = $matriculaprovincial = $matriculanacional = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    if($_SESSION["Perfil"] == "medico")
    {
        $espcialidad = $_POST["especialidad"];
        $matriculanacional = $_POST["matriculanacional"];
        $matriculaprovincial = $_POST["matriculaprovincial"];
    }
    $pass = $_POST["pass"];
    // Prepare an insert statement
    $sql = "UPDATE  usuarios set nombre=?, apellido=?, dni = ?, especialidad = ?,
     matriculanacional = ?, matriculaprovincial = ? where user=?";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7);

        // Set parameters
        $p1 = $nombre;
        $p2 = $apellido;
        $p3 = $dni;
        $p4 = $espcialidad;
        $p5 = $matriculanacional;
        $p6 = $matriculaprovincial;
        $p7 = $_SESSION["Usuario"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records created successfully. Redirect to landing page
            $_SESSION['Nombre'] = $nombre;
            $_SESSION['Apellido'] = $apellido;
            if ($pass != "") {
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                if ($link->query("update usuarios set pass='" . $pass . "' where user='" . $_SESSION["Usuario"] . "'")) {
                    header("location:index.php");
                } else {
                    echo "Ocurrio un error.";
                }
            }
            header("location:index.php");
        } else {
            echo "Ocurrio un error.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);


    // Close connection
    mysqli_close($link);
} else {
    // Get URL parameter

    // Prepare a select statement
    $sql = "SELECT * FROM usuarios WHERE user = ? and baja='False' ";
    //echo $sql;
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_id);

        // Set parameters
        $param_id = $_SESSION["Usuario"];

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
                $matriculanacional = $row["matriculanacional"];
                $matriculaprovincial = $row["matriculaprovincial"];
                $dni = $row["dni"];
                $especialidad = $row["especialidad"];
                // $dni = $row["DNI"];
            } else {
                // URL doesn't contain valid id. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                MODIFICACIÓN DE DATOS
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input class="form-control" required id="nombre" name="nombre" maxlength="20" placeholder="Nombre" value='<?php echo $nombre; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" required class="form-control" id="apellido" name="apellido" maxlength="20" placeholder="Apellido" value='<?php echo $apellido; ?>'>
                            </div>
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" required class="form-control" id="dni" name="dni" minlength="6" maxlength="15" value='<?php echo $dni; ?>'>
                            </div>
                            <?php
                            if($_SESSION["Perfil"]=="medico")
                            {
                                echo "
                            <div class='form-group'>
                                <label>Especialidad</label>
                                <input type='text' required class='form-control' id='especialidad' name='especialidad' maxlength='50' placeholder='Especialidad' value='<?php echo $especialidad; ?>'>
                            </div>
                            <div class='form-group'>
                                <label>Matricula Nacional</label>
                                <input type='text' class='form-control' id='matriculanacional' name='matriculanacional' minlength='6' maxlength='15' value='<?php echo $matriculanacional; ?>'>
                            </div>
                            <div class='form-group'>
                                <label>Matricula Provincial</label>
                                <input type='text' class='form-control' id='matriculaprovincial' name='matriculaprovincial' minlength='6' maxlength='15' value='<?php echo $matriculaprovincial; ?>'>
                            </div>
                            ";
                            }
                            ?>
                            <div class="form-group">
                                <label>Contraseña(Vacío para matener la misma)</label>
                                <input class="form-control" id="pass" name="pass" minlength="8" maxlength="30" placeholder="Contraseña">
                            </div>
                            <button type="submit" class="btn btn-default">Modificar</button>
                        </form>
                    </div>

                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->