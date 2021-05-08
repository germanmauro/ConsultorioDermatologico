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
               <a onclick="paginaPrincipal('turnos.php')" class="btn btn-success pull-right">Atrás</a>
               <h3 class="menu-text">Selección de duración del turno</h3>
               <h4 class="menu-text">Dr/a: <?= $turno->medico->apellido . ', ' . $turno->medico->nombre ?> </h4>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Duración del turno</label>
                                   <select required name="duracion" class="form-control">
                                     <option value='30'>30 min</option>
                                     <option value='60'>60 min</option>
                                     <option value='90'>90 min</option>
                                     <option value='120'>120 min</option>
                                   </select>
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
           var duracion = document.envio.duracion.value;
           cargaDuracion(duracion);
        });
   </script>