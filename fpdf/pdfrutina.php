<?php
require('fpdf.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );

class PDF_Invoice extends FPDF
{
// private variables
var $colonnes;
var $format;
var $angle=0;

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur )
{
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($texte, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$ligne = $texte;
		}
		else
		{
			$ligne  = substr( $texte, $index, $pos);
			$texte = substr( $texte, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $ligne ) );
		$res = 1 + floor( $length / $largeur) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

	function addNombre($paciente)
	{
		$this->SetXY(128,27);
		$this->SetFont("Arial", "", 10);
		$length = $this->GetStringWidth($paciente);
		$this->Cell($length, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addFecha($paciente)
	{
		$this->SetXY(133,36);
		$this->SetFont("Arial", "", 10);
		$length = $this->GetStringWidth($paciente);
		$this->Cell($length, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addTipoPiel($paciente)
	{
		$this->SetXY(87,70.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addHigieneDia1($paciente)
	{
		$this->SetXY(40,100.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addHigieneDia2($paciente)
	{
		$this->SetXY(39,110.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaContornoOjos($paciente)
	{
		$this->SetXY(36,128.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaBarreraCutanea($paciente)
	{
		$this->SetXY(39,140);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}
	
	function addDiaVitaminaC($paciente)
	{
		$this->SetXY(56,146);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaAcido($paciente)
	{
		$this->SetXY(46,153);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaHumectante($paciente)
	{
		$this->SetXY(57,160.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaCuello($paciente)
	{
		$this->SetXY(49,167.5);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaProtectorSolar($paciente)
	{
		$this->SetXY(64,175);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addDiaMaquillaje($paciente)
	{
		$this->SetXY(57,182);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addHigieneNoche1($paciente)
	{
		$this->SetXY(123,102);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addHigieneNoche2($paciente)
	{
		$this->SetXY(122,109);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addHigieneNoche3($paciente)
	{
		$this->SetXY(122,117);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addNocheContornoOjos($paciente)
	{
		$this->SetXY(118,144);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addNocheSerum($paciente)
	{
		$this->SetXY(132,151);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addNocheAcido($paciente)
	{
		$this->SetXY(132,160);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addNocheHumectante($paciente)
	{
		$this->SetXY(142,169);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addNocheCuello($paciente)
	{
		$this->SetXY(133,178);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addCuidadoHigiene($paciente)
	{
		$this->SetXY(55,212);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addCuidadoHumectacion($paciente)
	{
		$this->SetXY(65,221);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addCuidadoEspecial($paciente)
	{
		$this->SetXY(70,230);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}

	function addSuplementacion($paciente)
	{
		$this->SetXY(83,245);
		$this->SetFont("Arial", "", 10);
		$this->Cell(10, 0, utf8_decode($paciente), 0, 0, "L");
	}


}
?>
