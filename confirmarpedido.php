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

   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>

               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>
                       <div class='col-lg-12'>
                           <h3>Fechas de entrega disponibles para el pedido<h4>
                                   <form name="envio" id="envio" role="form" action="" method="post">
                                       <div class="form-group">
                                           <?php
                                            foreach ($pedido->zona->listaFechas as $item) {
                                                echo "<br>
                                                        <label><input type='radio' required name='fecha' value='" . $item->format('Y-m-d') . "'>" . $pedido->zona->nombreDias[$item->format('N') - 1] . " " . $item->format('d/m/Y') . "</label>";
                                            }
                                            ?>

                                       </div>
                                       <button type="submit" id="Send" name="Send" class="btn btn-success">Confirmar</button>
                                       <a class="btn btn-danger" onclick=paginaPrincipal('pedido.php')> CANCELAR</a>
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
   <script>
       $("#envio").on("submit", function(e) {
           $('#Send').attr("disabled", true);
           e.preventDefault();
           var ele = document.getElementsByName('fecha');
           var fecha = "";
           for (i = 0; i < ele.length; i++) {
               if (ele[i].checked)
                   fecha = ele[i].value;
           }

           generarPedido(fecha);
       });
   </script>