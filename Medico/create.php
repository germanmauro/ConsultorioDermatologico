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
$nombre = $apellido = $dni = $especialidad = $matriculanacional = $matriculaprovincial =
    $user = $pass = "";

//Cargar filtro

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $especialidad = $_POST["especialidad"];
    $matriculanacional = $_POST["matriculanacional"];
    $matriculaprovincial = $_POST["matriculaprovincial"];
    $user = $_POST["user"];
    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);

    // Prepare an insert statement
    $sql = "INSERT INTO usuarios (nombre,apellido,dni,especialidad,matriculanacional,matriculaprovincial,
    user,pass,perfil)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'medico')";

    if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8);

        // Set parameters
        $p1 = $nombre;
        $p2 = $apellido;
        $p3 = $dni;
        $p4 = $especialidad;
        $p5 = $matriculanacional;
        $p6 = $matriculaprovincial;
        $p7 = $user;
        $p8 = $pass;

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
                        <h2>Registrar Médico</h2>
                    </div>
                    <p> Ingrese los datos del nuevo médico</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input class="form-control" required id="nombre" name="nombre" maxlength="20" placeholder="Nombre" value='<?php echo $nombre; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" required class="form-control" id="apellido" name="apellido" maxlength="20" placeholder="Apellido" value='<?php echo $apellido; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Especialidad</label>
                            <input type="text" placeholder="Especialidad" required class="form-control" id="especialidad" name="especialidad" maxlength="50" placeholder="Especialidad" value='<?php echo $especialidad; ?>'>
                        </div>
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" placeholder="DNI" required class="form-control" id="dni" name="dni" minlength="6" maxlength="15" value='<?php echo $dni; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Matricula Nacional</label>
                            <input type="text" placeholder="Matrícula Nacional" class="form-control" id="matriculanacional" name="matriculanacional" minlength="6" maxlength="15" value='<?php echo $matriculanacional; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Matricula Provincial</label>
                            <input type="text" placeholder="Matrícula Provincial" class="form-control" id="matriculaprovincial" name="matriculaprovincial" minlength="6" maxlength="15" value='<?php echo $matriculaprovincial; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" placeholder="Usuario" class="form-control" id="user" name="user" minlength="6" maxlength="20" value='<?php echo $user; ?>'>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input class="form-control" placeholder="Contraseña" id="pass" name="pass" minlength="8" maxlength="30" placeholder="Contraseña">
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