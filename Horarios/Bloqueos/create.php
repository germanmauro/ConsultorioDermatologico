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
$dia = $desde = $hasta = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dia = $_POST["dia"];
    
        // Prepare an insert statement
        $sql = "INSERT INTO bloqueos (medico_id,dia)
     VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $p1, $p2);

            // Set parameters
            $p1 = $medico->id;
            $p2 = $dia;
    
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
                        <h2>Bloquear día para el médico <?=$medico->nombre." ".$medico->apellido?></h2>
                    </div>
                    <p> Ingrese el día</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Día</label>
                                <input class="form-control" type="date" name="dia" required>
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