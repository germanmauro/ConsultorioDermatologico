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
               <a onclick="paginaPrincipal('turnospaciente.php')" class="btn btn-success pull-right">Atrás</a>
               <h3 class="menu-text">Observaciones</h3>
               <h4 class="menu-text">Dr/a: <?= $turno->medico->apellido . ', ' . $turno->medico->nombre ?> </h4>
               <h4 class="menu-text">Paciente: <?= $turno->paciente->apellido . ', ' . $turno->paciente->nombre ?> </h4>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Observaciones</label>
                                   <textarea class="form-control" required name="observaciones" maxlength="300" rows="3"></textarea>
                               </div>

                               <button type="submit" id="Send" name="Send" class="btn btn-success">Genearar Turno</button>
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
           var observaciones = document.envio.observaciones.value;

           var parametros = {
               "observaciones": observaciones
           };
           swal("¿Desea generar el turno?", {
                   icon: "warning",
                   buttons: {
                       catch: {
                           text: "SÍ",
                           value: "catch",
                       },
                       cancel: "NO",
                   },
               })
               .then((value) => {
                   switch (value) {

                       case "catch":
                           $.ajax({
                               url: 'Accion/generarTurno.php',
                               type: 'POST',
                               data: parametros,
                               cache: false,
                               success: function(r) {
                                   $('#Send').attr("disabled", false);
                                   if (r != "") {
                                       swal(r, {
                                           buttons: false,
                                           icon: "error",
                                           timer: 3000,
                                       });
                                       //return r;
                                   } else {
                                       paginaPrincipal('turnoconfirma.php');
                                       swal("Turno generado con éxito", {
                                           buttons: false,
                                           icon: "success",
                                           timer: 4000,
                                       });

                                   }
                               }
                           });
                           break;
                   }
               });
       });
   </script>