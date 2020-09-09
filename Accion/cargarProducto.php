<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
session_start();
if(isset($_POST["id"])&&isset($_POST["cantidad"])){
    $pedido = new Pedido();
    $pedido = unserialize($_SESSION["Pedido"]);
    $pedido->agregarProducto($_POST["id"],$_POST["cantidad"]);
    $_SESSION["Pedido"] = serialize($pedido);
}
?>