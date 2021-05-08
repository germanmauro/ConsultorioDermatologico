<?php
session_start();
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["formapago"])) {
    $venta = new Venta();
    $venta = unserialize($_SESSION["Venta"]);
    $venta->formapago = new FormaPago($_POST["formapago"]);
    $venta->calcularTotal();
    $_SESSION["Venta"] = serialize($venta);
}
?>