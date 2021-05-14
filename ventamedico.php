   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/venta.php';
    $venta = new Venta();
    if (isset($_SESSION["Venta"])) {
        $venta = unserialize($_SESSION["Venta"]);
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
               <a onclick="

               paginaPrincipal('ventatratamiento.php')
               " class="btn btn-success pull-right">Atrás</a>
               <h4 class="menu-text">Paciente <?= $venta->paciente->apellido . ", " . $venta->paciente->nombre; ?></h4>
               <h4 class="menu-text">Fecha <?= date_format(date_create($venta->fecha), 'd/m/Y') ?> - Factura N° <?= $venta->factura ?></h4>
               <h3 class="menu-text">Selección de Médico</h3>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <select required name="medico" class="form-control">

                                       <?php

                                        $result = $link->query("select * from usuarios 
                            where baja='False' and (perfil='medico' or perfil='submedico') order by apellido");

                                        foreach ($result as $row) {
                                            $sel = "";
                                            if ($row["id"] == $venta->medico->id) {
                                                $sel = "selected";
                                            }
                                            echo "<option " . $sel . " value='" . $row['id'] . "'>" . $row["apellido"] . ", " . $row["nombre"] . "</option>";
                                        }

                                        ?>
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
                       e.preventDefault();
                       var medico = document.envio.medico.value;
                       cargaVentaMedico(medico);
                       });
       </script>