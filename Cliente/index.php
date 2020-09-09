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
if (!isset($_SESSION['Perfil']) || empty($_SESSION['Perfil']) || ($_SESSION['Perfil']) != 'admin') {
    header("location: ../index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
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
            $('#tabla').DataTable();
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
                        <h2 class="pull-left">Clientes</h2>
                        <!-- <a href="create.php" class="btn btn-success pull-right">Registrar Personal</a> -->
                    </div>

                    <br>
                    <?php
                    // Include config file
                    require_once '../config.php';


                    $sql = "SELECT usuario.Nombre as Nombre,Apellido,Direccion,DNI,
                    CONCAT(zona.Nombre,' - ',zona.Ubicacion) as Zona, Telefono,Email,usuario.Baja as Baja
                    FROM usuario
                    join zona on zona.Id = usuario.Zona
                    where 
                    Perfil = 'Cliente' 
                    order by Apellido,Nombre";
                    // echo $sql;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table id='tabla' class='display menutable'>";
                            echo "<thead>";
                            echo "<tr>";

                            echo "<th>Apellido</th>";
                            echo "<th>Nombre</th>";
                            // echo "<th>DNI</th>";
                            echo "<th>Dirección</th>";
                            echo "<th>Zona</th>";
                            echo "<th>Teléfono</th>";
                            echo "<th>E-Mail</th>";
                            echo "<th>Habilitado</th>";

                            echo "<th width='135px'>Acciones</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['Apellido'] . "</td>";
                                echo "<td>" . $row['Nombre'] . "</td>";
                                // echo "<td>" . $row['DNI'] . "</td>";
                                echo "<td>" . $row['Direccion'] . "</td>";
                                echo "<td>" . $row['Zona'] . "</td>";
                                echo "<td>" . $row['Telefono'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" ;
                                echo ($row['Baja']=='False')?'Si':'No';
                                echo "</td>";
                                echo "<td>";
                                if($row['Baja'] == 'True'){
                                    echo "<a onclick=habilitar('".$row['DNI']."') title='Habilitar Cliente' data-toggle='tooltip'><span class='glyphicon glyphicon-ok'></span></a>";
                                } else {
                                    echo "<a onclick=deshabilitar('".$row['DNI']."') title='Deshabilitar Cliente' data-toggle='tooltip'><span class='glyphicon glyphicon-remove'></span></a>";
                                }
                                // echo "<a href='delete.php?id=" . $row['User'] . "' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
    <script>
  function habilitar(user) {
      $.ajax({
        url: 'habilitar.php?User=' + user,
        //data: cat,
        cache: false,
        success: function(r) {
          if (r != "") {
            alert(r);
            //return r;
          } else {
            location.reload();
            alert('registro actualizado');
          }
        }
      });
    

  }

  function deshabilitar(user) {
      $.ajax({
        url: 'deshabilitar.php?User=' + user,
        //data: cat,
        cache: false,
        success: function(r) {
          if (r != "") {
            alert(r);
            //return r;
          } else {
            location.reload();
            alert('registro actualizado');
          }
        }
      });

  }
</script>
</body>

</html>