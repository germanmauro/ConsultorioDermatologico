<?php
require_once '../config.php';
require_once '../Clases/turno.php';
session_start();
if(isset($_POST["hora"])){
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $listahoras = $_POST["hora"];
    
    $turno->hora = $listahoras[0];
    $turno->duracion = 15 * count($listahoras);
    $_SESSION["Turno"] = serialize($turno);
}
?>