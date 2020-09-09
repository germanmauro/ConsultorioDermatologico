   <?php
    session_start();
    if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
        echo "<script>
    location.reload();
    </script>";
    }
    require_once 'config.php';
    require_once 'Clases/pedido.php';
    $categoria = 0;
    if (isset($_GET["categoria"])) {
        $categoria = $_GET["categoria"];
    }
    $pedido = new Pedido();
    if (isset($_SESSION["Pedido"])) {
        $pedido = unserialize($_SESSION["Pedido"]);
        if ($pedido->numero > 0) //Si vengo de una modificación
        {
            $pedido = new Pedido();
            $pedido->cargarCliente($_SESSION["Usuario"]);
            $_SESSION["Pedido"] = serialize($pedido);
        }
    } else {
        $pedido->cargarCliente($_SESSION["Usuario"]);
        $_SESSION["Pedido"] = serialize($pedido);
    }
    ?>
   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>

               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>
                       <div class='col-lg-12'>
                           <div class="app">

                               <ul class="hs">
                                   <?php
                                    // Include config file
                                    require_once 'config.php';
                                    $result = $link->query("select * from categoria where baja='False' order by Prioridad,Nombre");
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li onclick=paginaPrincipal('pedido.php?categoria=" . $row["Id"] . "') class='item".($row["Prioridad"]==0?' oferta':'')."'>" . $row["Nombre"] . "</li>";
                                    }
                                    ?>
                                   <li onclick=paginaPrincipal('pedido.php?categoria=0') class='item'>Todas</li>
                               </ul>
                           </div>
                       </div>
                   </div>
                   <!-- /.row -->
               </div>
               <div class='panel-body' id="sub-pagina">
                   <div class='row'>

                       <div class='col-lg-12'>
                           <div id="cantidadpedidos" class="app">
                               <?php
                                if (count($pedido->_colDetalle) > 0) {
                                    echo "<a onclick=paginaPrincipal('pedidodetalle.php') class='btn btn-success pull-right pedido'><i class='fas fa-shopping-basket'></i>   PEDIDO     ("   . count($pedido->_colDetalle) . " productos)</a>";
                                }
                                ?>
                               <div class="mensajeAlerta">
                                   <h4>
                                       <?php
                                        $result = $link->query("select Leyenda from configuracion");
                                        $row = mysqli_fetch_array($result);
                                        echo "<i class='fas fa-exclamation-triangle'></i>  " . $row["Leyenda"];
                                        ?>
                                   </h4>
                               </div>
                           </div>

                           <div class='table-responsive'>
                               <table id='tablemenu' class='display menutable'>
                                   <thead>
                                       <tr>
                                           <th>Producto</th>
                                           <th>Cantidad</th>
                                           <th>Agregar</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                        $result = $link->query("select producto.Id as Id,producto.Nombre as Nombre,Precio,Imagen,
                                       categoria.Nombre as Categoria, producto.Tipo as Tipo
                                       from producto
                                       join categoria on categoria.Id = producto.Categoria
                                        where (producto.Categoria=" . $categoria . " or 0 =" . $categoria . ")
                                        and producto.Baja='False' and Habilitado='Si'");
                                        while ($row = mysqli_fetch_array($result)) {
                                            $tipo = "";
                                            if ($row["Tipo"] == "Unidad") {
                                                $tipo = "Unidad";
                                            } else {
                                                $tipo = "Kg";
                                            }
                                            echo "<td>";
                                            echo "<div class='img'>" . $row["Nombre"] . "</div>";
                                            echo "<img src='Producto/" . $row["Imagen"] . "' class='menu-images' />";
                                            echo "<div class='img'>$ " . $row["Precio"] . " Por " . $tipo . "</div>";
                                            echo "</td>";
                                            echo "<td align='center'>";
                                            $j = 1;
                                            if ($row["Tipo"] == "Kilo") {
                                                $j = 0.5;
                                            } elseif ($row["Tipo"] == "Kilo25") {
                                                $j = 0.25;
                                            }
                                            echo "<select class='inputmenu' id='" . $row['Id'] . "cantidad'>";

                                            for ($i = 0; $i <= 50; $i = $i + $j) {
                                                echo "<option value='" . $i . "'>" . $i . "</option><br>";
                                            }
                                            echo "</select>";
                                            echo "</td>";
                                            echo "<td>";
                                            echo "<button id='" . $row["Id"] . "'  onclick='cargarProducto(" . $row['Id'] . ")' class='btnmenu'><i class='fas fa-shopping-basket'></i></button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                   </tbody>
                               </table>

                           </div>
                           <!-- /.table-responsive -->
                       </div>
                   </div>
                   <!-- /.row -->
               </div>
               <!-- /.panel-body -->
           </div>
           <!-- /.panel -->
       </div>
       <!-- /.col-lg-8 -->
   </div>
   <!-- /.row -->

   <script type="text/javascript">
       $(document).ready(function() {
           $('#tablemenu').DataTable();
       });

       function habilitaBoton() {
           //    event.preventDefault;
           //    var cant = id + 'cantidad'
           //    var monto = document.getElementById(cant).value;
           //    var boton = '#' + id;
           //    if (monto == "" || monto < 1) {
           //        $(boton).attr("disabled", true);
           //    } else {
           //        if (Number.isInteger(parseFloat(monto))) {
           //            $(boton).attr("disabled", false);
           //        } else {
           //            $(boton).attr("disabled", true);
           //        }
           //    }
       }
   </script>