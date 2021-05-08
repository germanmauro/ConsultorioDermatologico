<?php
// Initialize the session 
session_start();

if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (
    !isset($_SESSION['Perfil']) || empty($_SESSION['Perfil'])
    || !in_array($_SESSION['Perfil'], ['medico', 'secretaria', 'admin'])
) {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$codigo = $nombre = $apellido = $telefono = $email = $dni = $direccion =
    $obrasocial = $carnet = $localidad = $fechanacimiento = $profesion = $referido = $alta = "";


// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $obrasocial = $_POST["obrasocial"];
    $carnet = $_POST["carnet"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $localidad = $_POST["localidad"];
    $fechanacimiento = $_POST["fechanacimiento"];
    $profesion = $_POST["profesion"];
    $referido = $_POST["referido"];
    $alta = $_POST["alta"];

    $sql = "UPDATE pacientes SET codigo=?, apellido=?, nombre=?, dni = ?,
     obrasocial_id = ?, carnet = ?, telefono = ?, email = ?, direccion = ?, localidad = ?, fechanacimiento = ?,
     profesion = ?, referido = ?, alta = ?
       WHERE id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssss",
            $p1,
            $p2,
            $p3,
            $p4,
            $p5,
            $p6,
            $p7,
            $p8,
            $p9,
            $p10,
            $p11,
            $p12,
            $p13,
            $p14,
            $param_id
        );

        // Set parameters
        $p1 = $codigo;
        $p2 = $apellido;
        $p3 = $nombre;
        $p4 = $dni;
        $p5 = $obrasocial;
        $p6 = $carnet;
        $p7 = $telefono;
        $p8 = $email;
        $p9 = $direccion;
        $p10 = $localidad;
        $p11 = $fechanacimiento;
        $p12 = $profesion;
        $p13 = $referido;
        $p14 = $alta;

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
        $sql = "SELECT * FROM pacientes WHERE id = ? ";
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
                    $codigo = $row["codigo"];
                    $apellido = $row["apellido"];
                    $nombre = $row["nombre"];
                    $obrasocial = $row["obrasocial_id"];
                    $carnet = $row["carnet"];
                    $dni = $row["dni"];
                    $email = $row["email"];
                    $telefono = $row["telefono"];
                    $direccion = $row["direccion"];
                    $localidad = $row["localidad"];
                    $fechanacimiento = $row["fechanacimiento"];
                    $profesion = $row["profesion"];
                    $referido = $row["referido"];
                    $alta = $row["alta"];
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
                            <div class="form-group">
                                <label>Código</label>
                                <input type="interger" name="codigo" required min=0 max=10000 class="form-control" value="<?php echo $codigo; ?>">
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="apellido" required maxlength=20 class="form-control" value="<?php echo $apellido; ?>">
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre" required maxlength=20 class="form-control" value="<?php echo $nombre; ?>">
                            </div>
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" name="dni" required maxlength=20 class="form-control" value="<?php echo $dni; ?>">
                            </div>
                            <div class="form-group">
                                <label>Obra Social</label>
                                <select required name="obrasocial" class="form-control">
                                    <?php
                                    $result = $link->query("select * from obrassociales where baja='False'");
                                    foreach ($result as $row) {
                                        echo "<option " .
                                            ($obrasocial == $row["id"] ? 'selected' : '') . "
                                         value='" . $row['id'] . "'>" . $row["nombre"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Número Carnet</label>
                                <input type="text" name="carnet" required maxlength=30 class="form-control" value="<?php echo $carnet; ?>">
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" required maxlength=20 class="form-control" value="<?php echo $telefono; ?>">
                            </div>
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="email" name="email" maxlength=200 class="form-control" value="<?php echo $email; ?>">
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="direccion" required maxlength=200 class="form-control" value="<?php echo $direccion; ?>">
                            </div>
                            <div class="form-group">
                                <label>Localidad</label>
                                <input type="text" name="localidad" required maxlength=100 class="form-control" value="<?php echo $localidad; ?>">
                            </div>
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" name="fechanacimiento" maxlength=200 class="form-control" value="<?php echo $fechanacimiento; ?>">
                            </div>
                            <div class="form-group">
                                <label>Profesion</label>
                                <input type="text" name="profesion" required maxlength=100 class="form-control" value="<?php echo $profesion; ?>">
                            </div>
                            <div class="form-group">
                                <label>Referido</label>
                                <input type="text" name="referido" required maxlength=200 class="form-control" value="<?php echo $referido; ?>">
                            </div>
                            <div class="form-group">
                                <label>Alta</label>
                                <input type="date" name="alta" required class="form-control" value="<?php echo $alta; ?>">
                            </div>

                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <input type="hidden" name="imganterior" value="<?php echo $imagen; ?>" />
                            <input type="submit" name="actualizar" class="btn btn-primary" value="Actualizar">
                            <a href="index.php" class="btn btn-default">Cancelar</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>