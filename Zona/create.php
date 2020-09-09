<?php
// Initialize the session
session_start();

// // If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: login.php");
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
$nombre = $ubicacion = $cantidadpedidos = $lunes = $martes = $miercoles = $jueves = $viernes = $sabado = $domingo = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $ubicacion = $_POST["ubicacion"];
    $cantidadpedidos = $_POST["cantidadpedidos"];
    $lunes = $_POST["lunes"];
    $martes = $_POST["martes"];
    $miercoles = $_POST["miercoles"];
    $jueves = $_POST["jueves"];
    $viernes = $_POST["viernes"];
    $sabado = $_POST["sabado"];
    $domingo = $_POST["domingo"];

    // Prepare an insert statement
    $sql = "INSERT INTO zona (Nombre,Ubicacion,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo,CantidadPedidos) VALUES (?,?,?,?,?,?,?,?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);

        // Set parameters
        $p1 = $nombre;
        $p2 = $ubicacion;
        $p3 = $lunes;
        $p4 = $martes;
        $p5 = $miercoles;
        $p6 = $jueves;
        $p7 = $viernes;
        $p8 = $sabado;
        $p9 = $domingo;
        $p10 = $cantidadpedidos;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records created successfully. Redirect to landing page
            header("location: index.php");
            //echo $fecha;
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
                        <h2>Registrar Zona</h2>
                    </div>
                    <p> Ingrese los datos de la nueva zona</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=30 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ubicación</label>
                            <input type="text" name="ubicacion" required maxlength=30 class="form-control" value="<?php echo $ubicacion; ?>">
                        </div>
                        <div class="form-group">
                            <label>Pedidos por Día</label>
                            <input type="number" name="cantidadpedidos" required min=0 max=10000 class="form-control" value="<?php echo $cantidadpedidos; ?>">
                        </div>
                        <h2>Días Habilitados</h2>
                        <div class="form-group">
                            <table width="100%">
                                <tr>
                                    <td width="25%">
                                        <label>Lunes</label>
                                        <select id="lunes" name="lunes" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Martes</label>
                                        <select id="martes" name="martes" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Miercoles</label>
                                        <select id="miercoles" name="miercoles" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Jueves</label>
                                        <select id="jueves" name="jueves" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                <tr>
                                <tr>
                                    <td width="25%">
                                        <label>Viernes</label>
                                        <select id="viernes" name="viernes" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Sábado</label>
                                        <select id="sabado" name="sabado" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                    <td width="25%">
                                        <label>Domingo</label>
                                        <select id="domingo" name="domingo" class="form-control" required>
                                            <option selected value='Si'>Sí</option>
                                            <option value='No'>No</option>
                                        </select>
                                    </td>
                                <tr>
                            </table>
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