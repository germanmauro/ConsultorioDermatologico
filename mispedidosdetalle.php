   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/pedido.php';
    $id = "";
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
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
       <div class='col-lg-12' id='detallepedido'>
           <h4>Detalle del pedido</h4>
           <div class='table-responsive'>
               <table class='table table-bordered table-striped'>
                   <thead>
                       <tr>
                           <th>Item</th>
                           <th>Cant</th>
                           <th>$ Un.</th>
                           <th>$ Total</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                        $result = $link->query("select pedidodetalle.Cantidad as Cantidad,
                                    pedidodetalle.Nombre as Nombre,producto.Imagen,producto.Tipo,
                                    pedidodetalle.Precio as Precio, pedidodetalle.Total as Total
                                    from pedidodetalle
                                    join producto on pedidodetalle.Producto=producto.Id
                                    where pedidodetalle.Pedido=" . $id);
                        while ($row = mysqli_fetch_array($result)) {
                            $tipo = "";
                            if ($row["Tipo"] == "Unidad") {
                                $tipo = "Unidad";
                            } else {
                                $tipo = "Kg";
                            }
                            echo "<tr>";
                            echo "<td>";
                            echo "<div class='img'>" . $row["Nombre"] . "</div>";
                            echo "<img src='Producto/" . $row["Imagen"] . "' class='menu-images' />";
                            echo "<div class='img'>Por " . $tipo . "</div>";
                            echo "</td>";
                            echo "<td align='center'>";
                            echo $row["Cantidad"];
                            echo "</td>";
                            echo "<td align='center'>";
                            echo $row["Precio"];
                            echo "</td>";
                            echo "<td align='center'>";
                            echo $row["Total"];
                            echo "</td>";

                            echo "</tr>";
                        }

                        ?>
                   </tbody>
               </table>
           </div>
       </div>
   </div>

   <script>
       var element = document.querySelector("#sub-pagina");

       // scroll to element
       element.scrollIntoView();
   </script>