<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
session_start();
if (isset($_POST["fecha"])) {
    $fecha = $_POST["fecha"];

    $pedido = new Pedido();
    $pedido = unserialize($_SESSION["Pedido"]);
    $dev = new Devolucion();
    $dev = $pedido->guardar($fecha);
    if($dev->flag>0)
    {
        echo $dev->mensaje;
    }
    else {
        //Si todo está bien vacío el pedido
        $pedido = new Pedido();
        $pedido->cargarCliente($_SESSION["Usuario"]);
    }
    $_SESSION["Pedido"] = serialize($pedido);
}
?>