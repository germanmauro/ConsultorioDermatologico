<?php
require_once '../config.php';
require_once '../Clases/venta.php';
session_start();
    $venta = unserialize($_SESSION["Venta"]);
    foreach ($venta->listaTratamientos as $item) {
        $item->precioLista = $_POST[$item->id];
    }

    if(isset($_POST["mantenerPrecio"]))
    {
        $venta->precioTratamientoIgual = $_POST["mantenerPrecio"]=="false"?false:true;
    }

    $venta->calcularTotalSinFP();
    $_SESSION["Venta"] = serialize($venta);

?>