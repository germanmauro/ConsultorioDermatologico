<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
session_start();
if(isset($_POST["id"])){
    $pedido = new Pedido();
    $pedido->cargar($_POST["id"]);
    $_SESSION["Pedido"] = serialize($pedido);
}
?>