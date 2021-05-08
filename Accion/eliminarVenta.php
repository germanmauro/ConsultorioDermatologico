<?php
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["id"])) {
    $venta = new Venta();
    $venta->id = $_POST["id"];
    $venta->cargaVenta($venta->id);
    $dev = new Devolucion();
    $dev = $venta->eliminar();
    if($dev->flag == 1)
    {
        echo $dev->mensaje;
    }
}
?>