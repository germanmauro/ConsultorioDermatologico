<?php
// Initialize the session
session_start();

// Include config file
require_once '../config.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
  header("location: ../login.php");
  exit;
}
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) == 'Cliente') {
  header("location: ../index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Configuración</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/estiloprincipal.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
            <?php

            ?>
            <h2 class="pull-left">Configuraciones de venta</h2>
          </div>


          <?php

          $sql = "  select * from configuracion";

          // echo $sql;
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
              echo "<tr>";

              
              echo "<th>Monto Mínimo de Envío</th>";
              echo "<th>Leyenda</th>";
              echo "<th width='135px'>Acciones</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";

                echo "<td><input type='number' id='montoenvio' step='any' maxlength=30 required class='form-control' value='" . $row['MontoEnvio'] . "'>";
                echo "</td>";
                echo "<td><input type='text' id='leyenda'  maxlength=150 required class='form-control' value='" . $row['Leyenda'] . "'>";
                echo "</td>";
                echo "<td>";
                echo "<a onclick='actualizar()' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-refresh'></span></a>";
                echo "</td>";
                echo "</tr>";
              }
              echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
            } else {
              echo "<p class='lead'><em>No hay registros.</em></p>";
            }
          } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
          }

          // Close connection
          mysqli_close($link);
          ?>
        </div>
      </div>
    </div>

  </div>
</body>
<script>
  function actualizar() {
    var monto = document.getElementById('montoenvio').value;
    var leyenda = document.getElementById('leyenda').value;
    
    if (isNaN(monto) || monto == "") {
      swal("Debe ingresar un valor númerico válido", {
        buttons: false,
        icon: "error",
        timer: 3000
      });
    } else {
      $.ajax({
        url: 'actualizar.php?Monto=' + monto + '&Leyenda=' + leyenda,
        //data: cat,
        cache: false,
        success: function(r) {
          if (r != "") {
            alert(r);
            //return r;
          } else {

            swal("Registro actualizado!", {
              buttons: false,
              icon: "success",
              timer: 3000
            });

          }
        }

      });

    }

  }
</script>

</html>