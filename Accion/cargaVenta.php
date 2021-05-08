<?php
session_start();
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["id"])) {
    $venta = new Venta();
    $venta->cargaVenta($_POST["id"]);
    $_SESSION["Venta"] = serialize($venta);
}
?>