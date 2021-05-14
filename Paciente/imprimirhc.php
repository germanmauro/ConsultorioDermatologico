<?php
require_once '../config.php';
require_once '../Clases/turno.php';
require_once '../fpdf/pdfhc.php';
// Initialize the session
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])){
 header("location: ../login.php");
  exit;
}

if (isset($_SESSION["Paciente"])) {
  $paciente = unserialize($_SESSION["Paciente"]);
  $pdf =  $pdf = new PDF_Invoice('P', 'mm', 'A4');
  $pagina = 0;

  $pagina += 1;
  $pdf->AddPage();
  $hoy = new DateTime();
  $pdf->addTituloReporte($hoy->format('d/m/Y'));
  $pdf->addPageNumber($pagina);
  // $pdf->addSubTitulo();
  $pdf->addNombre($paciente->apellido . " " . $paciente->nombre);
  $pdf->addDNI($paciente->dni);
  $pdf->addFechaNacimiento(date('d/m/Y', strtotime($paciente->fechanacimiento)));
  $fecha = new DateTime($paciente->fechanacimiento);
  $pdf->addEdad(date_diff($fecha, $hoy)->y);
  $pdf->addDireccion($paciente->direccion);
  $pdf->addLocalidad($paciente->localidad);
  $pdf->addTelefono($paciente->telefono);
  $pdf->addCorreo($paciente->email);
  $pdf->addAlta(date_format(date_create($paciente->alta), "d/m/Y"));
  $pdf->addProfesion($paciente->profesion);
  $pdf->Image('images/logo.jpg', 10, 10, 60, 15);
  $result = $link->query("select DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, detalle, motivo from consultas where paciente_id=".$paciente->id);
  
      $cols = array(
        "FECHA"    => 32,
        "MOTIVO"  => 64,
        "DETALLE"  => 94,
      );

      $pdf->addCols($cols,72);
      $cols = array(
        "FECHA"    => "C",
        "MOTIVO"  => "L",
        "DETALLE"  => "L",
      );
    
      $pdf->addLineFormat($cols);

      $y = 80;
      while($row = mysqli_fetch_array($result)) {
        if ($y>260) {
          $pagina += 1;
          $pdf->AddPage();
          $pdf->addTituloReporte($hoy->format('d/m/Y'));
          $pdf->addPageNumber($pagina);
          // $pdf->addSubTitulo();
          $pdf->addNombre($paciente->apellido . " " . $paciente->nombre);
          $pdf->addDNI($paciente->dni);
          $pdf->addFechaNacimiento(date('d/m/Y', strtotime($paciente->fechanacimiento)));
          $fecha = new DateTime($paciente->fechanacimiento);
          $hoy = new DateTime();
          $pdf->addEdad(date_diff($fecha, $hoy)->y);
          $pdf->addDireccion($paciente->direccion);
          $pdf->addLocalidad($paciente->localidad);
          $pdf->addTelefono($paciente->telefono);
          $pdf->addCorreo($paciente->email);
          $pdf->addAlta(date_format(date_create($paciente->alta), "d/m/Y"));
          $pdf->addProfesion($paciente->profesion);
          $pdf->Image('images/logo.jpg', 10, 10, 60, 15);

           $cols = array(
            "FECHA"    => 32,
            "MOTIVO"  => 64,
            "DETALLE"  => 94,
          );

          $pdf->addCols($cols,72);
          $cols = array(
            "FECHA"    => "C",
            "MOTIVO"  => "L",
            "DETALLE"  => "L",
          );

        $pdf->addLineFormat($cols);
        $y = 80;
      }
        $line = array(
          "FECHA"    => $row["fecha"],
          "MOTIVO"    => utf8_decode($row["motivo"]),
          "DETALLE"  => utf8_decode($row["detalle"]));
      $size = $pdf->addLine( $y, $line );
      $y   += $size + 4;
      }
  
  $pdf->Output("",$paciente->nombre.' '.$paciente->apellido.'.pdf');
} else {

  echo "Error";

}
?>
