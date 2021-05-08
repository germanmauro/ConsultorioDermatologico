<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (
    !isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) ||
    !in_array($_SESSION['Perfil'], ['medico', 'secretaria','admin'])
) {
    header("location: index.php");
    exit;
}
?>
<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$fecha = $proveedor = $producto = $cantidad = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST["fecha"];
    $producto = $_POST["producto"];
    $cantidad = $_POST["cantidad"];

    // Prepare an insert statement
    $sql = "INSERT INTO compras (fecha,producto_id,cantidad) VALUES (?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $p1, $p2, $p3);

        // Set parameters
        $p1 = $fecha;
        $p2 = $producto;
        $p3 = $cantidad;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $link->query("update productos set stock=stock+".$cantidad." where id=".$producto);
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
                        <h2>Registrar Compra</h2>
                    </div>
                    <p> Ingrese los datos de la nueva compra</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Fecha</label>
                            <?php 
                            $hoy = date_format(date_create(), "Y-m-d");
                            ?>
                            <input type="date" name="fecha"class="form-control" value="<?php echo $hoy; ?>">
                        </div>
                        <div class="form-group">
                            <label>Producto</label>
                            <select required name="producto" class="form-control">
                                <?php 
                                $result = $link->query("select * from productos where baja='False' order by codigo");
                                foreach ($result as $row) {
                                    echo "<option value='".$row['id']."'>".$row["codigo"]." - ".$row["denominacion"]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" required min="1" max="10000" class="form-control" value="<?php echo $carnet; ?>">
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