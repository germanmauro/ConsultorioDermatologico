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
                                if (count($venta->listaProductos) > 0) {
                                    echo "<h2 class='menutext'>Productos de la venta</h2>";

                                    echo "
                                            <div class='table-responsive'>
                                            <table class='table table-bordered table-striped'>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Cod.</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Lista</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

                                    foreach ($venta->listaProductos as $item) {

                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<a onclick='borrarItemProducto(" . $item->id . ")'  title='Eliminar Item' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
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
                                        echo "<input id='" . $item->id . "' type='number' value='" . $item->precioLista . "'>";
                                        echo "</td>";
                                        echo "<td align='center'>";
                                        echo $item->cantidad * $item->precioLista;
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    echo "  
                                                </tbody>
                                            </table>
                                            <label><input id='mantenerprecio' type='checkbox'" . ($venta->precioProductoIgual ? ' checked' : '') . " > Mantener precio</label>
                                            </div>";
                                } else {
                                    echo "<p class='infoicono'>No agregó ningun producto</p class='infoicono'>";
                                }
                                echo " <a onclick=cargaPrecio() class='btn btn-success pull-right pedido'>
                                            Siguiente</a>";
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
   <script>
       function cargaPrecio() {
           var form_data = new FormData();
           var inputs = document.getElementsByTagName("input");
           for (var i = 0; i < inputs.length; i += 1) {
               form_data.append(inputs[i].id, inputs[i].value);
           }
           if (inputs.length > 2) // Si hay un producto
           {
               form_data.append('mantenerPrecio', document.getElementById('mantenerprecio').checked);
           }

           $.ajax({
               url: 'Accion/cargaPrecioProducto.php',
               type: 'POST',
               data: form_data,
               cache: false,
               dataType: 'text',
               contentType: false,
               processData: false,
               success: function(r) {
                   if (r != "") {
                       swal(r, {
                           buttons: false,
                           icon: "error",
                           timer: 3000,
                       });
                   } else {
                       paginaPrincipal('ventatratamiento.php');
                   }
               }
           });

       }
   </script>