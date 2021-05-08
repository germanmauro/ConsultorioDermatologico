<?php
require_once '../config.php';
require_once '../Clases/venta.php';
require_once '../Clases/medico.php';
session_start();
$venta = unserialize($_SESSION["Venta"]);
if(count($venta->listaProductos) == 0 && count($venta->listaTratamientos) == 0)
{
    echo "No";
}
elseif(count($venta->listaTratamientos) > 0) {
    echo "tratamiento";
} else {
    echo " ";
}


?>