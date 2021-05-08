<?php
session_start();
require_once '../config.php';
require_once '../Clases/turno.php';

if(isset($_POST["id"])){
    $turno = new Turno();
    $medico = new Medico($_POST["id"]);
    $turno->medico = $medico;
 
    $_SESSION["Turno"] = serialize($turno);
}
?>