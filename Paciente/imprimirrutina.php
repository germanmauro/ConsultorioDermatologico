<?php
require_once '../config.php';
require_once '../Clases/turno.php';
require_once '../fpdf/pdfrutina.php';
// Initialize the session
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])){
 header("location: ../login.php");
  exit;
}

if (isset($_GET["id"])) {
  $paciente = new Paciente();
  $paciente->rutina = new Rutina();
  $paciente->rutina->cargar($_GET["id"]);
  $pdf =  $pdf = new PDF_Invoice('P', 'mm', 'A4');
  $pagina = 0;

  $pagina += 1;
  $pdf->AddPage();
  $pdf->Image('images/rutina.jpg', 10, 10, 190, 280);
  $pdf->addNombre($paciente->rutina->paciente->apellido . " " . $paciente->rutina->paciente->nombre);
  $pdf->addFecha(date_format(date_create($paciente->rutina->fecha), "d/m/Y"));

  $pdf->addTipoPiel($paciente->rutina->tipopiel);
  $pdf->addHigieneDia1($paciente->rutina->diahigiene1);
  $pdf->addHigieneDia2($paciente->rutina->diahigiene2);
  $pdf->addDiaContornoOjos($paciente->rutina->diacontornoojos);
  $pdf->addDiaBarreraCutanea($paciente->rutina->diabarreracutanea);
  $pdf->addDiaVitaminaC($paciente->rutina->diavitaminac);
  $pdf->addDiaAcido($paciente->rutina->diaacido);
  $pdf->addDiaHumectante($paciente->rutina->diahumectante);
  $pdf->addDiaCuello($paciente->rutina->diacuello);
  $pdf->addDiaProtectorSolar($paciente->rutina->diaprotectorsolar);
  $pdf->addDiaMaquillaje($paciente->rutina->diamaquillaje);

  $pdf->addHigieneNoche1($paciente->rutina->nochehigiene1);
  $pdf->addHigieneNoche2($paciente->rutina->nochehigiene2);
  $pdf->addHigieneNoche3($paciente->rutina->nochehigiene3);
  $pdf->addNocheContornoOjos($paciente->rutina->nochecontornoojos);
  $pdf->addNocheSerum($paciente->rutina->nocheserum);
  $pdf->addNocheAcido($paciente->rutina->nocheacido);
  $pdf->addNocheHumectante($paciente->rutina->nochehumectante);
  $pdf->addNocheCuello($paciente->rutina->nochecuello);

  $pdf->addCuidadoHigiene($paciente->rutina->cuidadohigiene);
  $pdf->addCuidadoHumectacion($paciente->rutina->cuidadohumectacion);
  $pdf->addCuidadoEspecial($paciente->rutina->cuidadoespecial);

  $pdf->addSuplementacion($paciente->rutina->suplementacionviaoral);

  // $pdf->addFechaNacimiento(date('d/m/Y', strtotime($paciente->fechanacimiento)));
  // $fecha = new DateTime($paciente->fechanacimiento);
  // $pdf->addEdad(date_diff($fecha, $hoy)->y);
  // $pdf->addDireccion($paciente->direccion);
  // $pdf->addLocalidad($paciente->localidad);
  // $pdf->addTelefono($paciente->telefono);
  // $pdf->addCorreo($paciente->email);
  // $pdf->addAlta(date_format(date_create($paciente->alta), "d/m/Y"));
  // $pdf->addProfesion($paciente->profesion);

  
  
  $pdf->Output("",'rutina - ' . $paciente->rutina->paciente->apellido . " " . $paciente->rutina->paciente->nombre.'.pdf');
} else {

  echo "Error";

}
?>
