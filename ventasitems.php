   <?php
    session_start();
    require_once 'config.php';
 
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
                                
                                    echo "<h2 class='menutext'>Detalle de la venta</h2>";

                                    echo "
                                            <div class='table-responsive'>
                                            <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio U.</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                if (isset($_POST["Id"])) {
                                    $result = $link->query("SELECT preciounitario,total,cantidad,
                                    CONCAT(productos.codigo,' - ',productos.denominacion) as producto
                                    from ventasproductos
                                    join productos on productos.id = ventasproductos.producto_id
                                    where venta_id=" . $_POST["Id"]."
                                    UNION
                                    SELECT precioUnitario,total,cantidad,
                                    CONCAT(tratamientos.codigo,' - ',tratamientos.denominacion) as producto
                                    from ventastratamientos
                                    join tratamientos on tratamientos.id = ventastratamientos.tratamiento_id
                                    where venta_id=" . $_POST["Id"]);
                                    while($row = mysqli_fetch_array($result)){

                                        echo "<tr>";
                                        echo "<td align='center'>";
                                        echo $row["producto"];
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $row["cantidad"];
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $row["preciounitario"];
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $row["total"];
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                }
                                    echo "  
                                                </tbody>
                                            </table>
                                            </div>";
                               
                           
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