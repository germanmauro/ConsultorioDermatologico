   <?php
    // session_start();
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
               <h3 class="menu-text">Registro de venta</h3>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Fecha</label>
                                   <input type="date" name="fecha" class="form-control" required value="<?= $venta->fecha ?>">
                               </div>
                               <div class="form-group">
                                   <label>N° Factura</label>
                                   <input type="text" name="factura" class="form-control" value="<?= $venta->factura ?>">
                               </div>
                               <div class="form-group">
                                   <label>Observaciones</label>
                                   <textarea name="observaciones" rows="3" class="form-control"><?= $venta->observaciones ?></textarea>
                               </div>


                               <button type="submit" id="Send" name="Send" class="btn btn-success"> Siguiente</button>
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
           var fecha = document.envio.fecha.value;
           var factura = document.envio.factura.value;
           var observaciones = document.envio.observaciones.value;
           cargaFechaVenta(fecha, factura, observaciones);
       });
   </script>