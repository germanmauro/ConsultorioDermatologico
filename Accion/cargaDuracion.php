<?php
session_start();
require_once '../config.php';
require_once '../Clases/turno.php';
if (isset($_POST["duracion"])) {
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $turno->duracion = $_POST["duracion"];
    $_SESSION["Turno"] = serialize($turno);
}
?>