<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
session_start();
if(isset($_POST["zona"])&& isset($_POST["direccion"])&&isset($_POST["telefono"]) && isset($_POST["formapago"])){
    $pedido = new Pedido();
    $pedido = unserialize($_SESSION["Pedido"]);
    $pedido->agregarEntrega($_POST["zona"],$_POST["direccion"], $_POST["telefono"],$_POST["formapago"], $_POST["comentarios"]);
    $_SESSION["Pedido"] = serialize($pedido);
}
?>