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
$codigo = $denominacion = $stock = $marca = $preciocompra = $preciolista = $precioefectivo = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $denominacion = $_POST["denominacion"];
    $marca = $_POST["marca"];
    $stock = $_POST["stock"];
    $preciocompra = $_POST["preciocompra"];
    $preciolista = $_POST["preciolista"];
    $precioefectivo = $_POST["precioefectivo"];

    // Prepare an insert statement
    $sql = "INSERT INTO productos (codigo,denominacion,marca,stock,preciocompra,preciolista,precioefectivo)
     VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7);

        // Set parameters
        $p1 = $codigo;
        $p2 = $denominacion;
        $p3 = $marca;
        $p4 = $stock;
        $p5 = $preciocompra;
        $p6 = $preciolista;
        $p7 = $precioefectivo;

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
                        <h2>Registrar Producto</h2>
                    </div>
                    <p> Ingrese los datos del nuevo producto</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" name="codigo" required maxlength=10 class="form-control" value="<?php echo $codigo; ?>">
                        </div>
                        <div class="form-group">
                            <label>Denominación</label>
                            <input type="text" name="denominacion" required maxlength=200 class="form-control" value="<?php echo $denominacion; ?>">
                        </div>
                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" name="marca" required maxlength=100 class="form-control" value="<?php echo $marca; ?>">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" required min=0 max=100000000 name="stock" maxlength=50 class="form-control" value="<?php echo $stock; ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio Compra (Decimales separados por .)</label>
                            <input type="number" step="any" required min=1 max=100000000 name="preciocompra" maxlength=50 class="form-control" value="<?php echo $preciocompra; ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio Lista (Decimales separados por .)</label>
                            <input type="number" step="any" required min=1 max=100000000 name="preciolista" maxlength=50 class="form-control" value="<?php echo $preciolista; ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio Efectivo (Decimales separados por .)</label>
                            <input type="number" step="any" required min=1 max=100000000 name="precioefectivo" maxlength=50 class="form-control" value="<?php echo $precioefectivo; ?>">
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