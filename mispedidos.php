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
                           <table id='tablapedido' class='display menutable'>
                               <thead>
                                   <tr>
                                       <th>Fecha</th>
                                       <th>Entrega</th>
                                       <th>Total</th>
                                       <th></th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php
                                    $result = $link->query("select pedido.Id as Id,DATE_FORMAT(pedido.Fecha,'%d/%m/%y') as Fecha,
                                    DATE_FORMAT(pedido.FechaEntrega,'%d/%m/%y') as FechaEntrega,Total,FechaEntrega as Entrega
                                     
                                       from pedido
                                        where Cliente='" . $_SESSION["Usuario"] . "' and pedido.Estado='Generado'
                                        order by Id desc");
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo $row["Fecha"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["FechaEntrega"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["Total"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<button id='" . $row["Id"] . "'  onclick='cargadetalle(" . $row['Id'] . ")' class='btnmenu'><i class='fas fa-list'></i><br><span class='textboton'>Productos</span></button>";
                                        date_default_timezone_set('America/Argentina/Buenos_Aires');
                                        $hoy = date('Y-m-d');
                                        if($hoy<$row["Entrega"])
                                        {
                                            echo "<button id='" . $row["Id"] . "'  onclick='modificarpedido(" . $row['Id'] . ")' class='btnmenu'><i class='fas fa-edit'></i><br><span class='textboton'>Modificar</span></button>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                               </tbody>
                           </table>

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
           $('#tablapedido').DataTable({
               "pageLength": 3,
               "lengthMenu": [3, 10, 25, 50, 75, 100],
               "order": []
           });
       });