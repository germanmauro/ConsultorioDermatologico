<?php
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["id"])) {
    $venta = unserialize($_SESSION["Venta"]);
    $venta->eliminarTratamiento($_POST["id"]);
    $_SESSION["Venta"] = serialize($venta);
}
?>