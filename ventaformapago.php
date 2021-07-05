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
               <a onclick="paginaPrincipal('ventatratamiento.php')" class="btn btn-success pull-right">Atrás</a>
               <h4 class="menu-text">Paciente <?= $venta->paciente->apellido . ", " . $venta->paciente->nombre; ?></h4>
               <h4 class="menu-text">Fecha <?= date_format(date_create($venta->fecha), 'd/m/Y') ?> - Factura N° <?= $venta->factura ?></h4>
               <h4 class="menu-text">Cantidad de productos: <?= count($venta->listaProductos) ?> </h4>
               <h4 class="menu-text">Cantidad de tratamientos: <?= count($venta->listaTratamientos) ?> </h4>
               <h3 class="menu-text">Registro de venta</h3>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Forma de pago</label>
                                   <select onchange="cargaFormaPago()" required name="formapago" class="form-control">
                                       <option hidden disabled selected value='0'> -- seleccione una forma de pago -- </option>
                                       <?php
                                        $result = $link->query("select * from formaspago where baja='False' order by nombre");
                                        foreach ($result as $row) {
                                            $descuentoproducto = $row["porcentajeproducto"] - 100;
                                            if ($descuentoproducto > 0) {
                                                $descuentoproducto = "Producto (" . $descuentoproducto . "% Interés)";
                                            } else if ($descuentoproducto < 0) {
                                                $descuentoproducto = "Producto (" . abs($descuentoproducto) . "% Descuento)";
                                            } else {
                                                $descuentoproducto = "";
                                            }
                                            $descuentotratamiento = $row["porcentajetratamiento"] - 100;
                                            if ($descuentotratamiento > 0) {
                                                $descuentotratamiento = "Tratamiento (" . $descuentotratamiento . "% Interés)";
                                            } else if ($descuentotratamiento < 0) {
                                                $descuentotratamiento = "Tratamiento (" . abs($descuentotratamiento) . "% Descuento)";
                                            } else {
                                                $descuentotratamiento = "";
                                            }

                                            echo "<option " .
                                                ($venta->formapago->id == $row["id"] ? 'selected' : '') . "
                                            value='" . $row['id'] . "'>" . $row["nombre"] . "  " . $descuentoproducto . " " . $descuentotratamiento . "</option>";
                                        }
                                        ?>
                                   </select>
                               </div>

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
       $(document).ready(function() {
           var formapago = document.envio.formapago.value;
           if (formapago > 0) {
                subPagina('ventatotal.php');
                var formapago = document.envio.formapago.value;
                cargaFormaPagoVenta(formapago);
           }
       });
       $("#envio").on("submit", function(e) {
           $('#Send').attr("disabled", true);
           e.preventDefault();
           var formapago = document.envio.formapago.value;
           alert(formapago);
           cargaFormaPago(formapago);
       });

       cargaFormaPago = () => {
           var formapago = document.envio.formapago.value;
           cargaFormaPagoVenta(formapago);
       }
   </script>