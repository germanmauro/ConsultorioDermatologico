<?php
require_once '../config.php';
require_once '../Clases/paciente.php';
require_once '../Clases/devolucion.php';

session_start();
if(isset($_POST["fecha"])) {
    $paciente = new Paciente();
    $paciente = unserialize($_SESSION["Paciente"]);

    $paciente->rutina->paciente = $paciente->id;
    $paciente->rutina->id = $_POST["id"];
    $paciente->rutina->fecha = $_POST["fecha"];
    $paciente->rutina->tipopiel = $_POST["tipopiel"];
    $paciente->rutina->diahigiene1 = $_POST["diahigiene1"];
    $paciente->rutina->diahigiene2 = $_POST["diahigiene2"];
    $paciente->rutina->diacontornoojos = $_POST["diacontornoojos"];
    $paciente->rutina->diabarreracutanea = $_POST["diabarreracutanea"];
    $paciente->rutina->diavitaminac = $_POST["diavitaminac"];
    $paciente->rutina->diaacido = $_POST["diaacido"];
    $paciente->rutina->diahumectante = $_POST["diahumectante"];
    $paciente->rutina->diacuello = $_POST["diacuello"];
    $paciente->rutina->diaprotectorsolar = $_POST["diaprotectorsolar"];
    $paciente->rutina->diamaquillaje = $_POST["diamaquillaje"];

    $paciente->rutina->nochehigiene1 = $_POST["nochehigiene1"];
    $paciente->rutina->nochehigiene2 = $_POST["nochehigiene2"];
    $paciente->rutina->nochehigiene3 = $_POST["nochehigiene3"];
    $paciente->rutina->nochecontornoojos = $_POST["nochecontornoojos"];
    $paciente->rutina->nocheserum = $_POST["nocheserum"];
    $paciente->rutina->nocheacido = $_POST["nocheacido"];
    $paciente->rutina->nochehumectante = $_POST["nochehumectante"];
    $paciente->rutina->nochecuello = $_POST["nochecuello"];

    $paciente->rutina->cuidadohigiene = $_POST["cuidadohigiene"];
    $paciente->rutina->cuidadohumectacion = $_POST["cuidadohumectacion"];
    $paciente->rutina->cuidadoespecial = $_POST["cuidadoespecial"];
    
    $paciente->rutina->suplementacionviaoral = $_POST["suplementacionviaoral"];
    $dev = new Devolucion();
    $dev = $paciente->rutina->guardar();
    if ($dev->flag == 1) {
        echo $dev->mensaje;
    } 
    else {
        $paciente->rutina = new Rutina();
        $_SESSION["Paciente"] = serialize($paciente);
    }
}
?>