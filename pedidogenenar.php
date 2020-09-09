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
                           <div class="panel-heading">
                               CONFIRMAR DATOS DE ENTREGA
                           </div>
                           <div class="pedido">
                               <form name="envio" id="envio" role="form" action="" method="post">
                                   <div class="form-group">
                                       <label>Dirección o Barrio Privado</label>
                                       <input type="text" required class="form-control" id="direccion" name="direccion" maxlength="150" placeholder="Dirección o Barrio Privado" value='<?php echo $pedido->cliente->direccion; ?>'>
                                   </div>
                                   <div class="form-group">
                                       <label>Zona de Entrega</label>

                                       <select id="zona" name="zona" class="form-control" required>
                                           <?php
                                            $sql1 = "SELECT *
                                    FROM zona
                                    where Baja='False'
                                    order by Nombre";
                                            if ($result1 = mysqli_query($link, $sql1)) {
                                                if (mysqli_num_rows($result1) > 0) {
                                                    while ($row = mysqli_fetch_array($result1)) {
                                                        $sel = "";
                                                        if ($row["Id"] == $pedido->cliente->zona) {
                                                            $sel = "selected";
                                                        }
                                                        echo "<option " . $sel . "  value='" . $row["Id"] . "'>" . $row["Nombre"] . " - " . $row["Ubicacion"] . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                       </select>
                                   </div>
                                   <div class="form-group">
                                       <label>Forma de Pago</label>
                                       <select id="formapago" name="formapago" class="form-control" required>
                                           <option value='Efectivo'>Efectivo</option>
                                           <option value='Transferencia'>Transferencia</option>
                                           <option value='MercadoPago'>MercadoPago</option>
                                       </select>
                                   </div>

                                   <div class="form-group">
                                       <label>Teléfono</label>
                                       <input type="text" required class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Telefono" value='<?php echo $pedido->cliente->telefono; ?>'>
                                   </div>
                                   <div class="form-group">
                                       <label>Observaciones o Comentarios</label>
                                       <input type="text" class="form-control" id="comentarios" name="comentarios" maxlength="120" placeholder="Observaciones o Comentarios">
                                   </div>
                                   <button type="submit" id="Send" name="Send" class="btn btn-default">Confirmar</button>
                               </form>
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
       $("#envio").on("submit", function(e) {
           $('#Send').attr("disabled", true);
           e.preventDefault();
           var telefono = document.envio.telefono.value;
           var direccion = document.envio.direccion.value;
           var zona = document.envio.zona.value;
           var formapago = document.envio.formapago.value;
           var comentarios = document.envio.comentarios.value;

           var parametros = {
               "telefono": telefono,
               "direccion": direccion,
               "zona": zona,
               "formapago": formapago,
               "comentarios": comentarios
           };

           $.ajax({
               url: 'Accion/pedidoEntrega.php',
               type: 'POST',
               data: parametros,
               cache: false,
               success: function(response) {
                   if (response != '') {
                       swal(response, {
                           buttons: false,
                           icon: "error",
                           timer: 2000,
                       });
                       $('#Send').attr("disabled", false);
                   } else {
                       paginaPrincipal('confirmarpedido.php');
                   }
               }
           });
       });
   </script>