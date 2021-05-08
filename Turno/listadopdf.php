<?php
require_once '../config.php';
require_once '../Clases/turno.php';
require_once '../fpdf/pdflistado.php';
// Initialize the session
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])){
 header("location: ../../login.php");
  exit;
}

if(isset($_POST["medico"])&& isset($_POST["desde"])&& isset($_POST["hasta"]))
{
  $medico = $_POST["medico"];
  $desde = $_POST["desde"];
  $hasta = $_POST["hasta"];
  $turno = new TurnoListado($medico,$desde,$hasta);

  if(count($turno->listaTurnos)>0)
  {
    
    $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
    $pdf->AddPage();
    $titulo = "Agenda de turnos";
    if($medico!=0)
    {
      $doc = new Medico($medico);
      $titulo.= " de: ".$doc->apellido.", ".$doc->nombre;
    }
    $pdf->addTitulo($titulo);
    $pagina = 1;
    $pdf->addPageNumber($pagina);

    if($medico==0)
    {
      $cols = array(
        "TURNO"    => 32,
        "MEDICO"    => 35,
        "PACIENTE"  => 35,
        "TRATAMIENTO"  => 44,
        "OBSERVACIONES"  => 44
      );

      $pdf->addCols($cols, 30);
      $cols = array(
        "TURNO"    => "C",
        "MEDICO"    => "C",
        "PACIENTE"  => "C",
        "TRATAMIENTO"  => "C",
        "OBSERVACIONES"  => "C"
      );
    } else {
      $cols = array(
        "TURNO"    => 32,
        "PACIENTE"  => 40,
        "TRATAMIENTO"  => 64,
        "OBSERVACIONES"  => 54
      );

      $pdf->addCols($cols, 30);
      $cols = array(
        "TURNO"    => "C",
        "PACIENTE"  => "C",
        "TRATAMIENTO"  => "C",
        "OBSERVACIONES"  => "C"
      );
    }
    
      $pdf->addLineFormat($cols);

      $y = 38;
      foreach ($turno->listaTurnos as $det) {
        if ($y>240) {
        $pagina += 1;
        $pdf->AddPage();
        $pdf->addPageNumber($pagina);
        if ($medico == 0) {
          $cols = array(
            "TURNO"    => 32,
            "MEDICO"    => 45,
            "PACIENTE"  => 45,
            "TRATAMIENTO"  => 68
          );

          $pdf->addCols($cols, 30);
          $cols = array(
            "TURNO"    => "C",
            "MEDICO"    => "C",
            "PACIENTE"  => "C",
            "TRATAMIENTO"  => "C"
          );
        } else {
          $cols = array(
            "TURNO"    => 32,
            "PACIENTE"  => 50,
            "TRATAMIENTO"  => 108
          );

          $pdf->addCols($cols, 30);
          $cols = array(
            "TURNO"    => "C",
            "PACIENTE"  => "C",
            "TRATAMIENTO"  => "C"
          );
        }
        $pdf->addLineFormat($cols);
        $y = 38;
      }
      $fecha = new DateTime($det->fecha);
      $fechahasta = new DateTime($det->fecha);
      $fechahasta = $fechahasta->add(new DateInterval("PT".$det->duracion."M"));
        $line = array(
          "TURNO"    => $fecha->format("d/m/Y H:i")." a ".$fechahasta->format("H:i"),
          "MEDICO"    => utf8_decode($det->medico),
          "PACIENTE"  => utf8_decode($det->paciente),
          "TRATAMIENTO"  => utf8_decode($det->tratamiento),
          "OBSERVACIONES"  => utf8_decode($det->observaciones==""?" ":$det->observaciones));
      $size = $pdf->addLine( $y, $line );
      $y   += $size + 4;
      }

    $pdf->Output();
  }
  else{

    echo "<h1>No hay turnos con los valores seleccionados!</h1>";
  }
} else {

  echo "Error";

}
?>
