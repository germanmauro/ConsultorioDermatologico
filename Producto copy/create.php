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
$codigo = $nombre = $categoria = $precio = $imagen = $tipo = $habilitado = $stock = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $imagen = "images/sinimagen.jpg";
    }
    // Prepare an insert statement
    $sql = "INSERT INTO producto (Nombre,Categoria,Precio,Imagen,Tipo,Habilitado,Stock,Codigo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8);

        // Set parameters
        $p1 = $nombre;
        $p2 = $categoria;
        $p3 = $precio;
        $p4 = $imagen;
        $p5 = $tipo;
        $p6 = $habilitado;
        $p7 = $stock;
        $p8 = $codigo;

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
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=100 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <label>Categoría</label>
                                        <?php
                                        echo "<a href='../Categoria/create.php?Retorno=OK'>Nueva Categoría</a>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select id="categoria" name="categoria" class="form-control" required>
                                            <?php
                                            $sql1 = "SELECT *
                                                FROM categoria
                                                where Baja='False'
                                                order by Nombre";
                                            if ($result1 = mysqli_query($link, $sql1)) {
                                                if (mysqli_num_rows($result1) > 0) {
                                                    while ($row = mysqli_fetch_array($result1)) {

                                                        echo "<option value='" . $row["Id"] . "'>" . $row["Nombre"] . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>

                                </tr>

                            </table>
                        </div>
                        <div class="form-group">
                            <label>Precio (Decimales separados por .)</label>
                            <input type="number" step="any" required min=0 max=100000000 name="precio" maxlength=50 class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" required min=1 max=100000000 name="stock" maxlength=50 class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value='Kilo'>Por Kilo 0.5</option>
                                <option value='Kilo25'>Por Kilo 0.25</option>
                                <option value='Unidad'>Por Unidad</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Imagen</label>
                            <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control" value="<?php echo $imagen; ?>">
                        </div>
                        <div class="form-group">
                            <label>Habilitado</label>
                            <select id="habilitado" name="habilitado" class="form-control" required>
                                <option selected value='Si'>Sí</option>
                                <option value='No'>No</option>
                            </select>
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