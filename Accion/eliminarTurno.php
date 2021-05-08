<?php
require_once '../config.php';
require_once '../Clases/turno.php';
require_once '../Clases/medico.php';
session_start();
if(isset($_POST["id"])){
    $turno = new Turno();
    $turno->id = $_POST["id"];
    $turno->eliminar();
}
?>