<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
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
require_once '../../Clases/medico.php';

$medico = unserialize($_SESSION["Medico"]);
// Define variables and initialize with empty values
$dia = $desde = $hasta = $motivo = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST["dia"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];
    $motivo = $_POST["motivo"];
    $horadesde = new DateTime('2020-01-01 ' . $desde);
    $horahasta = new DateTime('2020-01-01 ' . $hasta);
    if ($horadesde < $horahasta) {
        // Prepare an insert statement
        $sql = "INSERT INTO bloqueosparciales (medico_id,dia,horadesde,horahasta,motivo)
     VALUES (?, ?, ?, ?,?)";

        if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $p1, $p2, $p3, $p4, $p5);

            // Set parameters
            $p1 = $medico->id;
            $p2 = $dia;
            $p3 = $desde;
            $p4 = $hasta;
            $p5 = $motivo;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Ocurrio un error.";
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
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Registro</title>
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
                        <h2>Generar día para el médico <?= $medico->nombre . " " . $medico->apellido ?></h2>
                    </div>
                    <p> Ingrese los datos del nuevo día</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Día</label>
                                <input required type="date" name="dia" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Desde</label>
                                <select required name="desde" class="form-control">
                                    <?php
                                    for ($i = 0; $i < 24; $i++) {
                                        echo "<option value='" . $i . ":00'>" . $i . ":00</option>";
                                        echo "<option value='" . $i . ":30'>" . $i . ":30</option>";
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
                                    echo "<option value='0:30'>0:30</option>";
                                    for ($i = 1; $i < 24; $i++) {
                                        echo "<option value='" . $i . ":00'>" . $i . ":00</option>";
                                        echo "<option value='" . $i . ":30'>" . $i . ":30</option>";
                                    }
                                    echo "<option value='24:00'>24:00</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivo</label>
                                <input type="text" name="motivo" class="form-control">
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Crear">

                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>