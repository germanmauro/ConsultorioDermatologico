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
$empresa = $contacto1 = $direccion = $telefono = $email = $datosbancarios = $comentarios =
$telefono1 = $email1 = $contacto2 = $telefono2 = $email2= "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = $_POST["empresa"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $datosbancarios = $_POST["datosbancarios"];
    $contacto1 = $_POST["contacto1"];
    $telefono1 = $_POST["telefono1"];
    $email1 = $_POST["email1"];
    $contacto2 = $_POST["contacto2"];
    $telefono2 = $_POST["telefono2"];
    $email2 = $_POST["email2"];
    $comentarios = $_POST["comentarios"];

    // Prepare an insert statement
    $sql = "INSERT INTO proveedores (empresa,direccion,telefono,email,datosbancarios,
    contacto1,telefono1, email1,contacto2,telefono2, email2, comentarios)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12);

        // Set parameters
        $p1 = $empresa;
        $p2 = $direccion;
        $p3 = $telefono;
        $p4 = $email;
        $p5 = $datosbancarios;
        $p6 = $contacto1;
        $p7 = $telefono1;
        $p8 = $email1;
        $p9 = $contacto2;
        $p10 = $telefono2;
        $p11 = $email2;
        $p12 = $comentarios;

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
                        <h2>Registrar Proveedor</h2>
                    </div>
                    <p> Ingrese los datos del nuevo proveedor</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Empresa</label>
                            <input type="text" name="empresa" required maxlength=100 class="form-control" value="<?php echo $empresa; ?>">
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="direccion" required maxlength=200 class="form-control" value="<?php echo $direccion; ?>">
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" required maxlength=20 class="form-control" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" name="email" maxlength=200 class="form-control" value="<?php echo $email; ?>">
                        </div>
                        <div class="form-group">
                            <label>Datos Bancarios</label>
                            <textarea class="form-control" name="datosbancarios" cols="30" rows="7" maxlength="2000"><?php echo $datosbancarios; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Contacto 1</label>
                            <input type="text" name="contacto1" required maxlength=100 class="form-control" value="<?php echo $contacto1; ?>">
                        </div>
                        <div class="form-group">
                            <label>Teléfono 1</label>
                            <input type="text" name="telefono1" required maxlength=20 class="form-control" value="<?php echo $telefono1; ?>">
                        </div>
                        <div class="form-group">
                            <label>E-mail 1</label>
                            <input type="email" name="email1" maxlength=200 class="form-control" value="<?php echo $email1; ?>">
                        </div>
                        <div class="form-group">
                            <label>Contacto 2</label>
                            <input type="text" name="contacto2" required maxlength=100 class="form-control" value="<?php echo $contacto2; ?>">
                        </div>
                        <div class="form-group">
                            <label>Teléfono 2</label>
                            <input type="text" name="telefono2" required maxlength=20 class="form-control" value="<?php echo $telefono2; ?>">
                        </div>
                        <div class="form-group">
                            <label>E-mail 2</label>
                            <input type="email" name="email2" maxlength=200 class="form-control" value="<?php echo $email2; ?>">
                        </div>
                        <div class="form-group">
                            <label>Comentarios</label>
                            <textarea class="form-control" name="comentarios" cols="30" rows="10" maxlength="2000"><?php echo $comentarios; ?></textarea>
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