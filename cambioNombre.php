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
$nombre = $apellido = $pass = $passrepeat = $direccion = $zona = $dni = $email = $telefono = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $direccion = $_POST["direccion"];
    $zona = $_POST["zona"];
    // $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $pass = $_POST["pass"];

    // Prepare an insert statement
    $sql = "UPDATE  usuario set Nombre=?, Apellido=?, Direccion = ?, Zona = ?, Telefono = ? where User=?";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssss", $p1, $p2, $p3, $p4, $p5, $p6);

        // Set parameters
        $p1 = $nombre;
        $p2 = $apellido;
        $p3 = $direccion;
        $p4 = $zona;
        // $p5 = $email;
        $p5 = $telefono;
        $p6 = $_SESSION["Usuario"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records created successfully. Redirect to landing page
            $_SESSION['Nombre'] = $nombre;
            $_SESSION['Apellido'] = $apellido;
            if ($pass != "") {
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                if ($link->query("update usuario set Pass='" . $pass . "' where User='" . $_SESSION["Usuario"] . "'")) {
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
    $sql = "SELECT * FROM usuario WHERE User = ? ";
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
                $nombre = $row["Nombre"];
                $apellido = $row["Apellido"];
                $direccion = $row["Direccion"];
                $telefono = $row["Telefono"];
                $email = $row["Email"];
                $zona = $row["Zona"];
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
                                <label>Dirección o Barrio Privado</label>
                                <input type="text" required class="form-control" id="direccion" name="direccion" maxlength="200" placeholder="Dirección o Barrio Privado" value='<?php echo $direccion; ?>'>
                            </div>
                            <div class="form-group">
                                <label>Zona de Entrega</label>

                                <select id="zona" name="zona" class="form-control" required>
                                    <?php
                                    $sql1 = "SELECT *
                                    FROM zona
                                    where Baja='False'
                                    order by Nombre";
                                    if ($result1 = mysqli_query($link, $sql1)) {
                                        if (mysqli_num_rows($result1) > 0) {
                                            while ($row = mysqli_fetch_array($result1)) {
                                                $sel = "";
                                                if ($row["Id"] == $zona) {
                                                    $sel = "selected";
                                                }
                                                echo "<option " . $sel . "  value='" . $row["Id"] . "'>" . $row["Nombre"] . " - " . $row["Ubicacion"] . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label>DNI (Necesario para ingresar al sistema)</label>
                                <input type="text" required class="form-control" id="dni" name="dni" minlength="6" disabled="true" maxlength="15" value='<?php echo $dni; ?>'>
                            </div> -->
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" required class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Telefono" value='<?php echo $telefono; ?>'>
                            </div>
                            <div class="form-group">
                                <label>E-Mail (Necesario para ingresar al sistema)</label>
                                <input type="email" class="form-control" disabled="true" name="email" id="email" maxlength="60" placeholder="E-Mail" value='<?php echo $email; ?>'>
                            </div>
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