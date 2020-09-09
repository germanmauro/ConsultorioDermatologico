<?php
session_start();
require_once 'config.php';
require_once 'Clases/pedido.php';
$pedido = new Pedido();
if (isset($_SESSION["Pedido"])) {
    $pedido = unserialize($_SESSION["Pedido"]);
}

if (count($pedido->_colDetalle) > 0) {
    if($pedido->numero>0)
    {
        echo "<a onclick=paginaPrincipal('pedidodetallemodificar.php') class='btn btn-success pull-right pedido'><i class='fas fa-shopping-basket'></i>   PEDIDO     ("   . count($pedido->_colDetalle) . " productos)</a>";
    }
    else
    {
        echo "<a onclick=paginaPrincipal('pedidodetalle.php') class='btn btn-success pull-right pedido'><i class='fas fa-shopping-basket'></i>   PEDIDO     ("   . count($pedido->_colDetalle) . " productos)</a>";
    }
    
}
echo"<div class='mensajeAlerta'>
<h4>";
                               
                               $result = $link->query("select Leyenda from configuracion");
                               $row = mysqli_fetch_array($result);
                                echo "<i class='fas fa-exclamation-triangle'></i>  " . $row["Leyenda"]."
                            
                           </h4>  
                           </div>";                       
?>