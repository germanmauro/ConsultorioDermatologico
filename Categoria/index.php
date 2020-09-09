<?php
// Initialize the session
session_start();

// Include config file
require_once '../config.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
  header("location: ../../login.php");
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
  <title>Gestión de Categorias</title>
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

</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <a href="../index.php" class="btn btn-success">Volver</a>
        <div class="col-md-12">
          <div class="page-header clearfix">
            <h2 class="pull-left">Categorías</h2>
            <a href="create.php" class="btn btn-success pull-right">Nueva Categoría</a>
          </div>


          <?php

          $sql = "Select categoria.Id as Id, categoria.Nombre as Nombre,Prioridad
          from categoria
          where Baja='False'
          order by Nombre";

          // echo $sql;
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
              echo "<tr>";

              echo "<th>Nombre</th>";
              echo "<th>Prioridad</th>";

              echo "<th>Acciones</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";

                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Prioridad'] . "</td>";

                echo "<td>";
                echo "<a href='update.php?id=" . $row['Id'] . "' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                echo "<a href='delete.php?id=" . $row['Id'] . "' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";

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

</html>