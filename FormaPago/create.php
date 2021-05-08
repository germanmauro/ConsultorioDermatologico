<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
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
$nombre = "";
$porcentajeproducto = "";
$porcentajetratamiento = "";
$retorno = "";

if (isset($_GET["Retorno"])) {
    $retorno = $_GET["Retorno"];
}
//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $retorno = $_POST["retorno"];

    $nombre = $_POST["nombre"];
    $porcentajeproducto = $_POST["porcentajeproducto"];
    $porcentajetratamiento = $_POST["porcentajetratamiento"];

    // Prepare an insert statement
    $sql = "INSERT INTO formaspago (nombre,porcentajeproducto,porcentajetratamiento) VALUES (?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $p1, $p2, $p3);

        // Set parameters
        $p1 = $nombre;
        $p2 = $porcentajeproducto;
        $p3 = $porcentajetratamiento;

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
                        <div class="form-group">
                            <label>Porcentaje del precio productos (Decimales separados por .) Ej: 100 = precio total, 10 = 10% del precio, 120 = 20% más del precio</label>
                            <input type="number" step="any" required min=0 max=300 name="porcentajeproducto" maxlength=50 class="form-control" value="<?php echo $porcentajeproducto; ?>">
                        </div>
                        <div class="form-group">
                            <label>Porcentaje del precio tratamientos (Decimales separados por .) Ej: 100 = precio total, 10 = 10% del precio, 120 = 20% más del precio</label>
                            <input type="number" step="any" required min=0 max=300 name="porcentajetratamiento" maxlength=50 class="form-control" value="<?php echo $porcentajetratamiento; ?>">
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