<?php
require_once '../config.php';
require_once '../Clases/turno.php';
session_start();
if(isset($_POST["codigo"])&&isset($_POST["nombre"])&&isset($_POST["apellido"]) &&
    isset($_POST["dni"]) && isset($_POST["obrasocial"]) &&
 isset($_POST["carnet"])&& isset($_POST["telefono"]) && isset($_POST["email"]) && isset($_POST["direccion"]) &&
isset($_POST["localidad"]) && isset($_POST["fechanacimiento"]) && isset($_POST["profesion"]) && isset($_POST["referido"])){
    $turno = new Turno();
    $turno = unserialize($_SESSION["Turno"]);
    $turno->agregarPaciente($_POST["codigo"], $_POST["nombre"], $_POST["apellido"],
        $_POST["dni"], $_POST["obrasocial"], $_POST["carnet"],
     $_POST["telefono"], $_POST["email"], $_POST["direccion"],$_POST["localidad"],$_POST["fechanacimiento"],
        $_POST["profesion"],$_POST["referido"]);
    $_SESSION["Turno"] = serialize($turno);   
}
?>