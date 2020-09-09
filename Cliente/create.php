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
$nombre = $apellido = $user  = $dni = $pass = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $user = $_POST["user"];
    $dni = $_POST["dni"];
    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    // Prepare an insert statement
    $sql = "INSERT INTO usuario (Apellido,Nombre,DNI,User,Pass,Perfil) VALUES (?, ?, ?, ?, ?, 'Mozo')";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssss", $p1, $p2, $p3, $p4, $p5);

        // Set parameters
        $p1 = $apellido;
        $p2 = $nombre;
        $p3 = $dni;
        $p4 = $user;
        $p5 = $pass;

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
    <link rel="stylesheet" href="../bootstrap.css">
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
                        <h2>Registrar Personal</h2>
                    </div>
                    <p> Ingrese los datos del nuevo personal</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="apellido" required maxlength=40 class="form-control" value="<?php echo $apellido; ?>">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" required maxlength=40 class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label>DNI/CUIT</label>
                            <input type="text" name="dni" maxlength=15 class="form-control" value="<?php echo $dni; ?>">
                        </div>
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" required name="user" maxlength=40 class="form-control" value="<?php echo $user; ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" required name="pass" maxlength=40 class="form-control">
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