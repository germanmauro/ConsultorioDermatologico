<?php
session_start();
require_once '../config.php';
require_once '../Clases/venta.php';

if(isset($_POST["id"])){
    $venta = unserialize($_SESSION["Venta"]);
    $medico = new Medico($_POST["id"]);
    $venta->medico = $medico;
 
    $_SESSION["Venta"] = serialize($venta);
}
?>