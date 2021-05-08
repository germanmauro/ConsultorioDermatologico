<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) ||
    !in_array($_SESSION['Perfil'], ['medico', 'admin'])) {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$codigo = $denominacion = $stock = $marca = $preciocompra = $precioventa = $precioefectivo = $fijo = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $denominacion = $_POST["denominacion"];
    $marca = $_POST["marca"];
    $stock = $_POST["stock"];
    $preciocompra = $_POST["preciocompra"];
    $precioventa = $_POST["precioventa"];
    $fijo = isset($_POST["fijo"]);
    if (mysqli_num_rows($link->query("select * from productos where codigo='" . $codigo . "' and baja = 'False'")) > 0) {
        echo "
            <div class='alert alert-warning' role='alert'>
            El código <b>" . $codigo . "</b> ya está utilizado
            </div>  ";
    } else {
        // Prepare an insert statement
        $sql = "INSERT INTO productos (codigo,denominacion,marca,stock,preciocompra,precioventa,fijo)
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
            $p6 = $precioventa;
            $p7 = $fijo;

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
                            <label>Proveedor</label>
                            <input type="text" name="marca" required maxlength=100 class="form-control" value="<?php echo $marca; ?>">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" required min=0 max=100000000 name="stock" maxlength=50 class="form-control" value="<?php echo $stock; ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio Compra + Imp. (Decimales separados por .)</label>
                            <input type="number" step="any" required min=1 max=100000000 name="preciocompra" maxlength=50 class="form-control" value="<?php echo $preciocompra; ?>">
                        </div>
                        <div class="form-group">
                            <label>Precio Lista (Decimales separados por .)</label>
                            <input type="number" step="any" required min=1 max=100000000 name="precioventa" maxlength=50 class="form-control" value="<?php echo $precioventa; ?>">
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="fijo" <?= $fijo ? ' checked' : '' ?>>
                                Precio Fijo
                            </label>
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