<?php
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();

$venta = unserialize($_SESSION["Venta"]);
$dev = new Devolucion();
$dev = $venta->guardar();
if($dev->flag == 1)
{
    $venta->id = "";
    echo $dev->mensaje;
}
else
{
    unset($_SESSION["Venta"]);
}


?>