<?php
session_start();
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
if (isset($_POST["fecha"])) {
    $venta = new Venta();
    $venta->paciente = new Paciente();
    if(isset($_SESSION["Venta"]))
    {
        $venta = unserialize($_SESSION["Venta"]);
    }
    $venta->fecha = $_POST["fecha"];
    $venta->factura = $_POST["factura"];
    $venta->observaciones = $_POST["observaciones"];
    $_SESSION["Venta"] = serialize($venta);
}
?>