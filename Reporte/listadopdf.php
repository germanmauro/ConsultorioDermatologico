<?php
require_once '../config.php';
require_once '../Clases/pedido.php';
require_once '../fpdf/pdfreporte.php';
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
  $pedido = new PedidoReporte($zona,$fecha);
}
if(count($pedido->_listaPedidos)>0)
{
  $pdf = new PDF_Invoice( 'P', 'mm', 'A4' );

  $pagina = 0;

//Por cada pedido
    $pagina+= 1;
    $pdf->AddPage();
    // $pdf->addSubTitulo();
    $pdf->addTituloReporte($pedido->zona->nombre . " - " . $pedido->zona->ubicacion);
    // $pdf->fact_dev();
    // $pdf->temporaire( "PRESUPUESTO" );
    
    $pdf->addDate(date_format(date_create($pedido->fecha), 'd/m/Y'));
    // $pdf->addClient("CL01");
    $pdf->addPageNumber($pagina);

    $total = 0.00;
    $cols=array(
                  "COD"    => 10,
                  "PRODUCTO"    => 100,
                  "CANTIDAD VENDIDA"  => 50,
                  "TOTAL $"  => 30);
    
    $pdf->addCols( $cols);
    $cols=array( 
                  "COD"    => "C",
                  "PRODUCTO"    => "L",
                  "CANTIDAD VENDIDA"  => "C",
                  "TOTAL $"     => "C");
    $pdf->addLineFormat( $cols);
    
    $y = 41;
    for ($i=0; $i < count($pedido->_listaPedidos) ; $i++) {
      if(in_array($i,[44,88,132])){
      $pagina += 1;
      $pdf->AddPage();
      $pdf->addPageNumber($pagina);
      $cols = array(
        "COD"    => 10,
        "PRODUCTO"    => 100,
        "CANTIDAD VENDIDA"  => 50,
        "TOTAL $"  => 30
      );

        $pdf->addCols($cols);
        $cols = array(
          "COD"    => "C",
          "PRODUCTO"    => "L",
          "CANTIDAD VENDIDA"  => "C",
          "TOTAL $"     => "C"
        );
        $pdf->addLineFormat($cols);
        $y = 41;
        
      }
      $tipo = "";
      if ($pedido->_listaPedidos[$i]->tipo == "Unidad") {
        $tipo = "Unidad";
      } else {
        $tipo = "Kg";
      }
      $line = array(
        "COD"  => ($pedido->_listaPedidos[$i]->codigo==""?" ": $pedido->_listaPedidos[$i]->codigo),
        "PRODUCTO"  => utf8_decode($pedido->_listaPedidos[$i]->nombre . " (por " . $tipo . ")"),
        "CANTIDAD VENDIDA"  => $pedido->_listaPedidos[$i]->cantidad,
        "TOTAL $"     => number_format($pedido->_listaPedidos[$i]->total, 2)
      );
      $size = $pdf->addLine($y, $line);
      $y   += $size + 2;
      $total += $pedido->_listaPedidos[$i]->total;
    }
    // foreach ($pedido->_listaPedidos as $detpro) {
    // $tipo = "";
    // if ($detpro->tipo == "Unidad") {
    //   $tipo = "Unidad";
    // } else {
    //   $tipo = "Kg";
    // }
    //   $line = array( "PRODUCTO"  => utf8_decode($detpro->nombre." (por ".$tipo.")"),
    //                 "CANTIDAD VENDIDA"  => $detpro->cantidad,
    //                 "TOTAL $"     => number_format($detpro->total,2));
    // $size = $pdf->addLine( $y, $line );
    // $y   += $size + 2;
    // $total += $detpro->total;
    // }
    $pdf->addTotal(number_format($total,2));
  

  $pdf->Output();
}
else{

  echo "<h1>No hay pedidos con los valores seleccionados!</h1>";
}
?>
