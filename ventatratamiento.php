   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/venta.php';
    if (isset($_SESSION["Venta"])) {
        $venta = unserialize($_SESSION["Venta"]);
    }

    ?>
   <link href="css/select2.css?v=3" rel="stylesheet">
   <script src="js/select2.js?v=3"></script>
   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <a onclick="paginaPrincipal('ventaproducto.php')" class="btn btn-success pull-right">Atrás</a>
               <h4 class="menu-text">Paciente <?= $venta->paciente->apellido . ", " . $venta->paciente->nombre; ?></h4>
               <h4 class="menu-text">Fecha <?= date_format(date_create($venta->fecha), 'd/m/Y') ?> - Factura N° <?= $venta->factura ?></h4>
               <!-- /.panel-heading -->

               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <div class='col-lg-12'>
                               <form autocomplete="off" name="envio" id="envio" role="form" action="" method="post">
                                   <div class="form-group">
                                       <label>Tratamiento</label>
                                       <select class="" id="selectProducto" name="tratamiento">
                                           <?php
                                            $result = $link->query("select * from tratamientos where baja='False' order by codigo");
                                            foreach ($result as $row) {
                                                echo "<option value='" . $row['id'] . "'>" . $row["codigo"] . " - " . $row["denominacion"] . "</option>";
                                            }
                                            ?>
                                       </select>
                                   </div>
                                   <div class="form-group">
                                       <label>Cantidad</label>
                                       <input type="number" required class="form-control" id="cantidad" name="cantidad" min=1 max=20 placeholder="Cantidad">
                                   </div>
                                   
                                   <button type="submit" id="Send" name="Send" class='btn-success'>Agregar Tratamiento</button>

                               </form>

                           </div>

                       </div>
                       <div id="sub-pagina">

                       </div> <!-- End Sub-Pagina -->
                   </div>
                   <!-- /.row -->

               </div>

           </div>
           <!-- /.panel -->

       </div>
   </div>
   <!-- /.col-lg-8 -->

   <script>
       $(document).ready(function() {
           subPagina('ventatratamientodetalle.php');
           // Initialize select2
           $("#selectProducto").select2();
       });
       $("#envio").on("submit", function(e) {
           e.preventDefault();
           var tratamiento = document.envio.tratamiento.value;
           var cantidad = document.envio.cantidad.value;
           cargaItemTratamiento(tratamiento, cantidad);

       });
   </script>