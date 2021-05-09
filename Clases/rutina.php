<?php
// require_once '../config.php';
require_once 'devolucion.php';

/********************
*** Clase Rutina  ***
*******************/
class Rutina
{
  //variables
    public $fecha = "";
    public $tipopiel = "";

    public $diahigiene1 = "";
    public $diahigiene2 = "";
    public $diacontornoojos = "";
    public $diabarreracutanea = "";
    public $diavitaminac = "";
    public $diaacido = "";
    public $diahumectante = "";
    public $diacuello = "";
    public $diaprotectorsolar = "";
    public $diamaquillaje = "";

    public $nochehigiene1 = "";
    public $nochehigiene2 = "";
    public $nochehigiene3 = "";
    public $nochecontornoojos = "";
    public $nocheserum = "";
    public $nocheacido = "";
    public $nochehumectante = "";
    public $nochecuello = "";

    public $cuidadohigiene = "";
    public $cuidadohumectacion = "";
    public $cuidadoespecial = "";

    public $suplementacionviaoral;
    public $paciente = "";

  function __construct($id)
  {
    $this->cargar($id);
  }

  function cargar($id)
  {
    global $link;
    $this->paciente = $id;
    $result = $link->query("select *
    from rutinas
    where paciente_id = ".$id);
    if(mysqli_num_rows($result)>0)
    {
      $row = mysqli_fetch_array($result);
      $this->fecha = $row["fecha"];
      $this->tipopiel = $row["tipopiel"];

      $this->diahigiene1 = $row["diahigiene1"];
      $this->diahigiene2 = $row["diahigiene2"];
      $this->diacontornoojos = $row["diacontornoojos"];
      $this->diabarreracutanea = $row["diabarreracutanea"];
      $this->diavitaminac = $row["diavitaminac"];
      $this->diaacido = $row["diaacido"];
      $this->diahumectante = $row["diahumectante"];
      $this->diacuello = $row["diacuello"];
      $this->diaprotectorsolar = $row["diaprotectorsolar"];
      $this->diamaquillaje = $row["diamaquillaje"];

      $this->nochehigiene1 = $row["nochehigiene1"];
      $this->nochehigiene2 = $row["nochehigiene2"];
      $this->nochehigiene3 = $row["nochehigiene3"];
      $this->nochecontornoojos = $row["nochecontornoojos"];
      $this->nocheserum = $row["nocheserum"];
      $this->nocheacido = $row["nocheacido"];
      $this->nochehumectante = $row["nochehumectante"];
      $this->nochecuello = $row["nochecuello"];

      $this->cuidadohigiene = $row["cuidadohigiene"];
      $this->cuidadohumectacion = $row["cuidadohumectacion"];
      $this->cuidadoespecial = $row["cuidadoespecial"];
    
      $this->suplementacionviaoral = $row["suplementacionviaoral"];
    }
    
  }
  function guardar()
  {
    global $link;
    $dev = new Devolucion();
    $link->autocommit(false);
    try {
      if (!$link->query("delete from rutinas where paciente_id =" . $this->paciente)) {
        $dev->mensaje = "Error al registrar Rutina";
        $dev->flag = 1;
        throw new Exception("Error al registrar Rutina");
      } else {
        if ($link->query("insert into rutinas (paciente_id, fecha, tipopiel, diahigiene1, diahigiene2,
       diacontornoojos,diabarreracutanea, diavitaminac, diaacido, diahumectante, diacuello, diaprotectorsolar,
      diamaquillaje, nochehigiene1, nochehigiene2, nochehigiene3, nochecontornoojos, nocheserum, nocheacido,
      nochehumectante, nochecuello, cuidadohigiene, cuidadohumectacion, cuidadoespecial,
      suplementacionviaoral)
      values(" . $this->paciente . ",'" . $this->fecha . "','" . $this->tipopiel . "','" . $this->diahigiene1 . "'
      ,'" . $this->diahigiene2 . "','" . $this->diacontornoojos . "','" .
        $this->diabarreracutanea . "','" . $this->diavitaminac . "','" . $this->diaacido . "','" . $this->diahumectante . "','" .
        $this->diacuello . "','" . $this->diaprotectorsolar . "','" . $this->diamaquillaje . "','" . $this->nochehigiene1 . "','" .
        $this->nochehigiene2 . "','" . $this->nochehigiene3 . "','" .$this->nochecontornoojos . "','" .  
        $this->nocheserum . "','" .$this->nocheacido . "','" . $this->nochehumectante . "','" . 
        $this->nochecuello . "','" . $this->cuidadohigiene . "','" .$this->cuidadohumectacion . "','" . 
        $this->cuidadoespecial . "','" . $this->suplementacionviaoral . "')")) {
          $link->commit();
          return $dev;
        } else {
          $dev->mensaje = "Error al registrar Rutina";
          $dev->flag = 1;
          throw new Exception("Error al registrar Rutina");
        }
      }
    } catch (\Throwable $th) {
      $link->rollback();
      return $dev;
    }    
  }

}
 ?>
