<?php
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["id"])&& isset($_POST["cantidad"])) {
    $venta = unserialize($_SESSION["Venta"]);
    $venta->agregarTratamiento($_POST["id"], $_POST["cantidad"]);
    $_SESSION["Venta"] = serialize($venta);
}
?>