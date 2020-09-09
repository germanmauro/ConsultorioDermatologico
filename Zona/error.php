<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])){
 header("location: ../../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style type="text/css">
        .wrapper{
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
                        <h1>Acción inválida</h1>
                    </div>
                    <div class="alert alert-danger fade in">
                        <p>Se ha producido un error <a href="index.php" class="alert-link">Volver</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
