<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
require_once '../fpdf/pdflistado.php';
// Initialize the session
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])){
 header("location: ../../login.php");
  exit;
}

//Obtengo el numero de facturar
$pedido = "";
if(isset($_POST["fecha"])&& isset($_POST["zona"]))
{
  $zona = $_POST["zona"];
  $fecha = $_POST["fecha"];
  $pedido = new PedidoListado($zona,$fecha);
}
if(count($pedido->_listaPedidos)>0)
{
  $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );

  $pagina = 0;

//Por cada pedido
  foreach ($pedido->_listaPedidos as $det) {
    $pagina+= 1;
    $pdf->AddPage();
    // $pdf->addSubTitulo();
    $pdf->addClient($det->cliente->nombre . " " . $det->cliente->apellido);
    $pdf->addFormaPago($det->formapago);
    $pdf->addComentarios($det->comentarios);
    // $pdf->fact_dev();
    // $pdf->temporaire( "PRESUPUESTO" );
    $pdf->addDate($det->fecha);
    $pdf->addFechaEntrega($det->fechaentrega);
    $pdf->addTelefono($det->telefono);
    $pdf->addDireccion($det->zona->nombre . " - ".$det->zona->ubicacion, $det->direccion);
    $pdf->addCantidad(count($det->_colDetalle));
    // $pdf->addClient("CL01");
    $pdf->addPageNumber($pagina);

    
    $cols=array( "COD."    => 10,
                  "CANTIDAD"    => 20,
                  "PRODUCTO"  => 80,
                  "PRECIO UN"  => 30,
                  "TOTAL"     => 50);
    
    $pdf->addCols( $cols);
    $cols=array( "COD."    => "C",
                  "CANTIDAD"    => "C",
                  "PRODUCTO"  => "L",
                  "PRECIO UN"  => "C",
                  "TOTAL"     => "C");
    $pdf->addLineFormat( $cols);
    
    $y = 65;
    foreach ($det->_colDetalle as $detpro) {
      if ($y>249) {
        $pagina += 1;
        $pdf->AddPage();
        $pdf->addPageNumber($pagina);
        $cols = array(
          "COD."    => 10,
          "CANTIDAD"    => 20,
          "PRODUCTO"  => 80,
          "PRECIO UN"  => 30,
          "TOTAL"   => 50
        );

        $pdf->addCols($cols,34);
        $cols = array(
          "COD."    => "C",
          "CANTIDAD"    => "C",
          "PRODUCTO"  => "L",
          "PRECIO UN"  => "C",
          "TOTAL"     => "C"
        );
        $pdf->addLineFormat($cols);
        $y = 42;
      }
      $tipo = "";
      if ($detpro->tipo == "Unidad") {
        $tipo = "Unidad";
      } else {
        $tipo = "Kg";
      }
      $line = array(
                    "COD."    => ($detpro->codigo==""? " ": $detpro->codigo), 
                    "CANTIDAD"    => $detpro->cantidad,
                    "PRODUCTO"  => utf8_decode($detpro->nombre." (por ".$tipo.")"),
                    "PRECIO UN"  => $detpro->precio,
                    "TOTAL"     => $detpro->total);
    $size = $pdf->addLine( $y, $line );
    $y   += $size + 2;
    }
    $pdf->addTotal($det->total);
    $result = $link->query("select Leyenda from configuracion");
    $row = mysqli_fetch_array($result);
    $pdf->addLeyenda($row["Leyenda"]);
  }

  $pdf->Output();
}
else{

  echo "<h1>No hay pedidos con los valores seleccionados!</h1>";
}
?>
