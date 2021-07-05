<?php
require_once '../config.php';
require_once '../Clases/paciente.php';
require_once '../Clases/devolucion.php';

session_start();
if(isset($_POST["id"])){
    $paciente = new Paciente();
    $paciente = unserialize($_SESSION["Paciente"]);

    $paciente->eliminarRutina($_POST["id"]);
}
?>