   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/venta.php';
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

               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>
                       <div class='col-lg-12'>
                           <div class="pedido">
                               <?php
                                    echo "<h2 class='menutext'>Productos de la venta</h2>";

                                    echo "
                                            <div class='table-responsive'>
                                            <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio U.</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

                                    foreach ($venta->listaProductos as $item) {

                                        echo "<tr>";
                                        echo "<td align='center'>";
                                        echo $item->codigo;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->denominacion;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->cantidad;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->precioUnitario;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->total;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    foreach ($venta->listaTratamientos as $item) {

                                        echo "<tr>";
                                        echo "<td align='center'>";
                                        echo $item->codigo;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->denominacion;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->cantidad;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->precioUnitario;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->total;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    echo "  
                                                </tbody>
                                            </table>
                                            Total $ ".$venta->total."
                                            </div>";
                                    if (count($venta->listaProductos) > 0 || count($venta->listaTratamientos) > 0) {
                                        echo "<a onclick=generarVenta() class='btn btn-success pull-right pedido'>
                                            Generar venta</a>";
                                    }
                             
                                ?>



                           </div>
                       </div>
                   </div>
                   <!-- /.row -->
               </div>

           </div>
           <!-- /.panel -->
       </div>
       <!-- /.col-lg-8 -->
   </div>
   <!-- /.row -->