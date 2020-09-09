<?php
// Initialize the session
session_start();


// Include config file
require_once '../config.php';
// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
  header("location: ../index.php");
  exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) != 'admin') {
  header("location: ../index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Pedidos</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/estiloprincipal.css">
  <script src="../js/jquery.min.js"></script>
  <script src=" ../js/bootstrap.min.js"> </script>
  <style type="text/css">
    .wrapper {

      margin: 0 auto;
    }

    .page-header h2 {
      margin-top: 0;
    }

    table tr td:last-child a {
      margin-right: 15px;
    }
  </style>
  <script type="text/javascript">
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <a href="../index.php" class="btn btn-success">Volver</a>
        <div class="col-md-12">
          <div class="page-header clearfix">
            <h2 class="pull-left">Reporte de Venta Diaria</h2>
            <!-- <a href="create.php" class="btn btn-success pull-right">Nuevo Cheque</a> -->
          </div>
          <form target="_blank" action="listadopdf.php" method="post">
            <div class="col-md-6">
              <div class="form-group">
                <label>Zona</label>
                <select name="zona" class='form-control'>
                  <option value="0">Todas</option>
                  <?php
                  $sql1 = "SELECT * from zona where Baja='False'
                                        order by Nombre";

                  if ($result1 = mysqli_query($link, $sql1)) {
                    if (mysqli_num_rows($result1) > 0) {
                      $sel = "";
                      while ($row = mysqli_fetch_array($result1)) {
                        echo "<option value='" . $row["Id"] . "'>" . $row["Nombre"] . " - " . $row["Ubicacion"] . "</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ">
                <label>Fecha de Entrega</label>
                <input type="date" name="fecha" required class="form-control">
              </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Generar Reporte Diario">
          </form>

        </div>
      </div>
    </div>

  </div>
</body>

</html>