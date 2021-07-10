<?php
require_once '../config.php';
require_once '../Clases/turno.php';
require_once '../Clases/medico.php';
session_start();
if(isset($_POST["fecha"])){
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $dev = new Devolucion();
    $dev = $turno->bloquear($_POST["fecha"]);
    if ($dev->flag == 1) {
        echo $dev->mensaje;
    }
}
?>