<?php
require_once '../config.php';
require_once '../Clases/turno.php';
session_start();
if(isset($_POST["observaciones"])){
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $turno->observaciones = $_POST["observaciones"];
        $dev = new Devolucion();
        $dev = $turno->guardar();
        if($dev->flag == 1)
        {
            echo $dev->mensaje;
        }
        $_SESSION["Turno"] = serialize($turno);   
}
?>