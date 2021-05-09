<?php
require_once '../config.php';
require_once '../Clases/paciente.php';
require_once '../Clases/devolucion.php';

session_start();
// if (isset($_POST["fecha"])) {
    if (0 < $_FILES['file']['error']) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
        $paciente = new Paciente();
        $paciente = unserialize($_SESSION["Paciente"]);
        
        if ($_FILES["file"]["name"] != "") {
            $directorio = "../Paciente/archivo/" . $_FILES["file"]["name"];
            $archivo = $_FILES["file"]["name"];
            $ruta = $_FILES["file"]["tmp_name"];
            copy($ruta, $directorio);
        } else {
            $archivo = "";
        }
        $paciente->registrarArchivo($_POST["fecha"], $_POST["descripcion"], $archivo);
    }

?>