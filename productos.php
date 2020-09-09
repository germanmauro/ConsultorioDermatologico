   <?php
    session_start();

    require_once 'config.php';

    $categoria = 0;
    if (isset($_GET["categoria"])) {
        $categoria = $_GET["categoria"];
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

                                    $result = $link->query("select * from categoria where baja='False' order by Prioridad,Nombre");
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li onclick=paginaPrincipal('productos.php?categoria=" . $row["Id"] . "') 
                                        class='item".($row["Prioridad"]==0?' oferta':'')."'>" . $row["Nombre"] . "</li>";
                                    }
                                    ?>
                                   <li onclick=paginaPrincipal('productos.php?categoria=0') class='item'>Todas</li>
                               </ul>
                           </div>
                       </div>
                   </div>
                   <!-- /.row -->
               </div>
               <div class='panel-body' id="sub-pagina">
                   <div class='row'>

                       <div class='col-lg-12'>

                           <?php
                           echo "<div class='row'>";
                            $result = $link->query("select producto.Id as Id,producto.Nombre as Nombre,Precio,Imagen,
                            categoria.Nombre as Categoria, producto.Tipo as Tipo
                            from producto
                            join categoria on categoria.Id = producto.Categoria
                            where (producto.Categoria=" . $categoria . " or 0 =" . $categoria . ")
                            and producto.Baja='False' and Habilitado='Si' order by Nombre");
                            while ($row = mysqli_fetch_array($result)) {
                                $tipo = "";
                                if ($row["Tipo"] == "Unidad") {
                                    $tipo = "Unidad";
                                } else {
                                    $tipo = "Kg";
                                }
                                echo "<div class='col-md-3 catalogo'>";
                                    echo "<div class='catalogo-in'>";
                                        echo "<div class='catalogo-texto'>" . $row["Nombre"] . "</div>";
                                        echo "<div class='catalogo-div-img'>";
                                            echo "<img src='Producto/" . $row["Imagen"] . "' class='catalogo-img' />";
                                        echo "</div>";
                                        echo "<div class='catalogo-texto'>$ " . $row["Precio"] . " Por " . $tipo . "</div>";
                                    echo "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                            ?>

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
   </script>