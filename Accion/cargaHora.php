<?php
require_once '../config.php';
require_once '../Clases/turno.php';
session_start();
if(isset($_POST["hora"])){
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $val = $turno->verificarHora($_POST["hora"]);
    if($val == 0)
    {
        $turno->hora = $_POST["hora"];
        $_SESSION["Turno"] = serialize($turno);
    }
    elseif($val==1)
    {
        echo "El turno se solapa con otros turnos";
    }
    elseif($val==3)
    {
        echo "El turno se solapa con bloqueos";
    } else {
        echo "El turno excede la agenda del profesional";
    }
}
?>