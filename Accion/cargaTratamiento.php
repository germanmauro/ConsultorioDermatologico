<?php
session_start();
require_once '../config.php';
require_once '../Clases/turno.php';
session_start();
if (isset($_POST["id"])) {
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $turno->observaciones = $_POST["observaciones"];
    $tratamiento = new Tratamiento($_POST["id"]);
    $turno->tratamiento = $tratamiento;
    $_SESSION["Turno"] = serialize($turno);
}
?>