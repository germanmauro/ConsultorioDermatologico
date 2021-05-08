   <?php
    // session_start();
    session_start();
    require_once 'config.php';
    require_once 'Clases/turno.php';
    $turno = new Turno();
    if (isset($_SESSION["Turno"])) {
        $turno = unserialize($_SESSION["Turno"]);
    }
    ?>
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
   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <a onclick="paginaPrincipal('turnosfecha.php')" class="btn btn-success pull-right">Atrás</a>
               <h3 class="menu-text">Selección de tratamientos</h3>
               <h4 class="menu-text">Dr/a: <?= $turno->medico->apellido . ', ' . $turno->medico->nombre ?> </h4>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Tratamiento</label>
                                   <select required name="tratamiento" class="form-control">
                                       <?php
                                        $result = $link->query("select * from tratamientos where baja='False'");
                                        foreach ($result as $row) {
                                            echo "<option value='" . $row['id'] . "'>" . $row["codigo"] . " - " . $row["denominacion"] . "</option>";
                                        }
                                        ?>
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>Observaciones</label>
                                   <textarea class="form-control" name="observaciones" maxlength="300" rows="3"></textarea>
                               </div>

                               <button type="submit" id="Send" name="Send" class="btn btn-success">Siguiente</button>
                           </form>

                       </div>

                   </div>
                   <div id="sub-pagina">

                   </div>
               </div>
               <!-- /.row -->

           </div>

       </div>
       <!-- /.panel -->

   </div>
   <!-- /.col-lg-8 -->


   <script type="text/javascript">
       $("#envio").on("submit", function(e) {
           $('#Send').attr("disabled", true);
           e.preventDefault();
           var tratamiento = document.envio.tratamiento.value;
           var observaciones = document.envio.observaciones.value;
           cargaTratamiento(tratamiento,observaciones);
        });
   </script>