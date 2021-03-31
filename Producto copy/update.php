<?php
// Initialize the session 
session_start();

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
$codigo = $nombre = $categoria = $precio = $imagen = $tipo = $habilitado = $stock = "";

// Processing form data when form is submitted
if (isset($_POST["actualizar"])) {
    // Get hidden input value

    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $categoria = $_POST["categoria"];
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $tipo = $_POST["tipo"];
    $habilitado = $_POST["habilitado"];
    $stock = $_POST["stock"];
    $precio = $_POST["precio"];
    if ($_FILES["imagen"]["name"] != "") {
        $imagen = "images/" . $_FILES["imagen"]["name"];
        $ruta = $_FILES["imagen"]["tmp_name"];
        copy($ruta, $imagen);
    } else {
        $imagen = $_POST["imganterior"];
    }

    $sql = "UPDATE producto SET Nombre=?, Categoria=?, Precio=?, Imagen = ?, Tipo = ?, Habilitado = ?, Stock = ?, Codigo = ?  WHERE Id=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $param_id);

        // Set parameters
        $p1 = $nombre;
        $p2 = $categoria;
        $p3 = $precio;
        $p4 = $imagen;
        $p5 = $tipo;
        $p6 = $habilitado;
        $p7 = $stock;
        $p8 = $codigo;

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
        $sql = "SELECT * FROM producto WHERE Id = ? ";
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
                    $nombre = $row["Nombre"];
                    $categoria = $row["Categoria"];
                    $imagen = $row["Imagen"];
                    $precio = $row["Precio"];
                    $tipo = $row["Tipo"];
                    $habilitado = $row["Habilitado"];
                    $stock = $row["Stock"];
                    $codigo = $row["Codigo"];
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
                            <input type="text" name="codigo" required maxlength=10 class="form-control" value="<?php echo $codigo; ?>">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=100 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label>Categoría</label>

                            <select id="categoria" name="categoria" class="form-control" required>
                                <?php
                                $sql1 = "SELECT *
                                    FROM categoria
                                    where Baja='False'
                                    order by Nombre";
                                if ($result1 = mysqli_query($link, $sql1)) {
                                    if (mysqli_num_rows($result1) > 0) {
                                        while ($row = mysqli_fetch_array($result1)) {
                                            if ($row["Id"] == $categoria) {
                                                $sel = "selected";
                                            } else {
                                                $sel = "";
                                            }
                                            echo "<option " . $sel . " value='" . $row["Id"] . "'>" . $row["Nombre"] . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Precio (Decimales separados por .)</label>
                            <input type="number" step="any" required min=0 max=100000000 name="precio" maxlength=50 class="form-control" value="<?php echo $precio; ?>">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" required min=1 max=100000000 name="stock" maxlength=50 class="form-control" value="<?php echo $stock; ?>">
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option <?php echo ($tipo == 'Kilo') ? 'selected' : ''; ?> value='Kilo'>Por Kilo 0.5</option>
                                <option <?php echo ($tipo == 'Kilo25') ? 'selected' : ''; ?> value='Kilo25'>Por Kilo 0.25</option>
                                <option <?php echo ($tipo == 'Unidad') ? 'selected' : ''; ?> value='Unidad'>Por Unidad</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Imagen (No cargar archivo para matener la imagen actual)</label>
                            <table width="100%">
                                <tr>
                                    <td>
                                        <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="<?php echo $imagen; ?>" width="200px" height="200px" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Habilitado</label>
                            <select id="habilitado" name="habilitado" class="form-control" required>
                                <option <?php echo ($habilitado == 'Si') ? 'selected' : ''; ?> value='Si'>Sí</option>
                                <option <?php echo ($habilitado == 'No') ? 'selected' : ''; ?> value='No'>No</option>
                            </select>
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