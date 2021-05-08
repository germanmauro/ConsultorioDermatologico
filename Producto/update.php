<?php
// Initialize the session 
session_start();

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

// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $denominacion = $_POST["denominacion"];
    $marca = $_POST["marca"];
    $stock = $_POST["stock"];
    $preciocompra = $_POST["preciocompra"];
    $precioventa = $_POST["precioventa"];
    $fijo = isset($_POST["fijo"]);

    $sql = "UPDATE productos SET codigo=?, denominacion=?, marca=?, stock = ?, preciocompra = ?,
     precioventa = ?, fijo = ?  WHERE id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $param_id);

        // Set parameters
        $p1 = $codigo;
        $p2 = $denominacion;
        $p3 = $marca;
        $p4 = $stock;
        $p5 = $preciocompra;
        $p6 = $precioventa;
        $p7 = $fijo;

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
        $sql = "SELECT * FROM productos WHERE id = ? ";
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
                    $denominacion = $row["denominacion"];
                    $marca = $row["marca"];
                    $stock = $row["stock"];
                    $preciocompra = $row["preciocompra"];
                    $precioventa = $row["precioventa"];
                    $fijo = $row["fijo"];
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