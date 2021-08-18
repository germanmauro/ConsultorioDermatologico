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
                                $fecha = date_format(date_create(), "Y-m-d");
                                
                                    echo "<h3 class='menutext'>Totales del "
                                . date_format(date_create(), "d/m/Y")."</h3>";

                                    echo "
                                            <div class='table-responsive'>
                                            <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th>Forma de Pago</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                    $result = $link->query("SELECT sum(total) as total,
                                    formaspago.nombre as formapago
                                    from ventas
                                    join formaspago on formaspago.id = ventas.formapago_id
                                    where fecha='".$fecha."'
                                    and (ventas.usuario_id =".$_SESSION["Id"]." or '".$_SESSION["Perfil"]."'
                                    in ('medico','admin'))
                                    group by formapago
                                    having total > 0");
                      
                                    while($row = mysqli_fetch_array($result)){

                                        echo "<tr>";
                                        echo "<td align='center'>";
                                        echo $row["formapago"];
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $row["total"];
                                        echo "</td>";

                                        echo "</tr>";
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