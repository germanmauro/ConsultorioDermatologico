<?php
require_once '../config.php';
require_once '../Clases/paciente.php';
require_once '../Clases/devolucion.php';

session_start();
if(isset($_POST["neurologico"])){
    $paciente = new Paciente();
    $paciente = unserialize($_SESSION["Paciente"]);
    $paciente->historia->neurologico = $_POST["neurologico"];
    $paciente->historia->cardiovascular = $_POST["cardiovascular"];
    $paciente->historia->endocrinologico = $_POST["endocrinologico"];
    $paciente->historia->pulmonar = $_POST["pulmonar"];
    $paciente->historia->digestivo = $_POST["digestivo"];
    $paciente->historia->renal = $_POST["renal"];
    $paciente->historia->dermatologico = $_POST["dermatologico"];
    $paciente->historia->hematologicas = $_POST["hematologicas"];
    $paciente->historia->antecedentesotros = $_POST["antecedentesotros"];

    $paciente->historia->antigangrenantes = $_POST["antigangrenantes"];
    $paciente->historia->anticoagulantes = $_POST["anticoagulantes"];
    $paciente->historia->analgesicos = $_POST["analgesicos"];
    $paciente->historia->suplementosvitaminicos = $_POST["suplementosvitaminicos"];
    $paciente->historia->antidepresivos = $_POST["antidepresivos"];
    $paciente->historia->medicamentosotros = $_POST["medicamentosotros"];

    $paciente->historia->alergiafarmaco = $_POST["alergiafarmaco"];
    $paciente->historia->alergiaanestesicolocal = $_POST["alergiaanestesicolocal"];

    $paciente->historia->tabaco = $_POST["tabaco"];
    $paciente->historia->alcohol = $_POST["alcohol"];
    $paciente->historia->actividadfisica = $_POST["actividadfisica"];
    $paciente->historia->exposicionsolar = $_POST["exposicionsolar"];

    $paciente->historia->toxinabotulinica = $_POST["toxinabotulinica"];
    $paciente->historia->acidohialuronico = $_POST["acidohialuronico"];
    $paciente->historia->antecedentesquirurgicos = $_POST["antecedentesquirurgicos"];

    $paciente->historia->antecedentestraumaticos = $_POST["antecedentestraumaticos"];
    $paciente->historia->cicatrizacion = $_POST["cicatrizacion"];
    $paciente->historia->reaccionesvagales = $_POST["reaccionesvagales"];
    $paciente->historia->dismorfofobia = $_POST["dismorfofobia"];
    $paciente->historia->vacunacionantitetanica = $_POST["vacunacionantitetanica"];
    $paciente->historia->fragilidadcapilar = $_POST["fragilidadcapilar"];
    $paciente->historia->tratamientoodontologico = $_POST["tratamientoodontologico"];
    $dev = new Devolucion();
    $dev = $paciente->historia->guardar();
    if ($dev->flag == 1) {
        echo $dev->mensaje;
    }
    $_SESSION["Paciente"] = serialize($paciente);
}
?>