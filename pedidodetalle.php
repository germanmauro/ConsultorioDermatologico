   <?php
    session_start();
    if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
        echo "<script>
    location.reload();
    </script>";
    }
    require_once 'config.php';
    require_once 'Clases/pedido.php';
    $pedido = new Pedido();
    if (isset($_SESSION["Pedido"])) {
        $pedido = unserialize($_SESSION["Pedido"]);
    }
    if($pedido->numero > 0){
        echo "<script>
            irPaginaPrincipal('pedidodetallemodificar.php');
        </script>";
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
                               if (count($pedido->_colDetalle) > 0) {
                                echo "<h1 class='menutext'>Detalle del pedido</h1>";
                                }
                               ?>
                               <a onclick="paginaPrincipal('pedido.php')" class="btn btn-success pedido">Seguir comprando</a>
                               <?php
                                if (count($pedido->_colDetalle) > 0) {
                                    global $link;
                                    $result = $link->query("select MontoEnvio from configuracion");
                                    $row = mysqli_fetch_array($result);
                                    $montoenvio = $row["MontoEnvio"];
                                    $pedido->calcularTotal();
                                    
                                    echo "
                                            <div class='table-responsive'>
                                            <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Item</th>
                                                    <th>Cant</th>
                                                    <th>$ Un.</th>
                                                    <th>$ Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

                                    foreach ($pedido->_colDetalle as $item) {
                                        $tipo = "";
                                        if ($item->tipo == "Unidad") {
                                            $tipo = "Unidad";
                                        } else {
                                            $tipo = "Kg";
                                        }
                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<a onclick='borrarProducto(" . $item->producto . ")' title='Eliminar Item' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<div class='img'>" . $item->nombre . "</div>";
                                        echo "<img src='Producto/" . $item->imagen . "' class='menu-images' />";
                                        echo "<div class='img'>Por " . $tipo . "</div>";
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->cantidad;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->precio;
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->total;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    echo "  
                                                </tbody>
                                            </table>
                                            </div>";
                                            
                                            echo "<h4>Total $ ".$pedido->total."</h4>";
                                    if ($pedido->total >= $montoenvio) {
                                        echo "<a onclick=paginaPrincipal('pedidogenenar.php') class='btn btn-success pull-right pedido'>Generar Pedido</a>";
                                    } else {
                                        echo "<h4>EL MONTO MÍNIMO PARA ENVÍO ES DE $ " . $montoenvio . "</h4>";
                                    }
                                } else {
                                    echo "<h1 class='menutext'>Aún no tiene productos cargados en la canasta</h2>";
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