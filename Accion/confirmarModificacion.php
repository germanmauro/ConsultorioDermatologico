<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
session_start();

$pedido = new Pedido();
$pedido = unserialize($_SESSION["Pedido"]);
$dev = new Devolucion();
$dev = $pedido->modificarPedido();
if ($dev->flag > 0) {
    echo $dev->mensaje;
} else {
    //Si todo está bien vacío el pedido
    $pedido = new Pedido();
    $pedido->cargarCliente($_SESSION["Usuario"]);
}
?>