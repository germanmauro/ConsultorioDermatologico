<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || 
!in_array($_SESSION['Perfil'],['medico','admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$nombre = "";
$retorno = "";

if (isset($_GET["Retorno"])) {
    $retorno = $_GET["Retorno"];
}
//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $retorno = $_POST["retorno"];

    $nombre = $_POST["nombre"];

    // Prepare an insert statement
    $sql = "INSERT INTO obrassociales (Nombre) VALUES (?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $p1);

        // Set parameters
        $p1 = $nombre;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records created successfully. Redirect to landing page
            if ($retorno == "OK") {
                // header("location: ../Producto/create.php?Retorno=OK");
            } else {
                header("location: index.php");
            }
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
                        <h2>Registrar Forma de pago</h2>
                    </div>
                    <p> Ingrese los datos de la nueva forma de pago</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=100 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        
                        <input type="hidden" name=retorno value="<?php echo $retorno ?>">
                        <input type="submit" class="btn btn-primary" value="Crear">

                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>