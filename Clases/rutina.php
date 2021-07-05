<?php
// require_once '../config.php';
require_once 'devolucion.php';

/********************
*** Clase Rutina  ***
*******************/
class Rutina
{
    //variables
    public $id = "";
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

  function __construct()
  {
    
  }

  function cargar($id)
  {
    global $link;
    $this->id = $id;
    $result = $link->query("select *
    from rutinas
    where id = ".$id);
    if(mysqli_num_rows($result)>0)
    {
      $row = mysqli_fetch_array($result);
      $this->paciente = new Paciente();
      $this->paciente->cargar($row["paciente_id"]);

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
        if($this->id < 1)
        {
          if ($link->query("insert into rutinas (paciente_id, fecha, tipopiel, diahigiene1, diahigiene2,
        diacontornoojos,diabarreracutanea, diavitaminac, diaacido, diahumectante, diacuello, diaprotectorsolar,
        diamaquillaje, nochehigiene1, nochehigiene2, nochehigiene3, nochecontornoojos, nocheserum, nocheacido,
        nochehumectante, nochecuello, cuidadohigiene, cuidadohumectacion, cuidadoespecial,
        suplementacionviaoral)
        values(" . $this->paciente . ",'" . $this->fecha . "','" . $this->tipopiel . "','" . $this->diahigiene1 . "'
        ,'" . $this->diahigiene2 . "','" . $this->diacontornoojos . "','" .
            $this->diabarreracutanea . "','" . $this->diavitaminac . "','" . $this->diaacido . "','" . $this->diahumectante . "','" .
            $this->diacuello . "','" . $this->diaprotectorsolar . "','" . $this->diamaquillaje . "','" . $this->nochehigiene1 . "','" .
            $this->nochehigiene2 . "','" . $this->nochehigiene3 . "','" . $this->nochecontornoojos . "','" .
            $this->nocheserum . "','" . $this->nocheacido . "','" . $this->nochehumectante . "','" .
            $this->nochecuello . "','" . $this->cuidadohigiene . "','" . $this->cuidadohumectacion . "','" .
            $this->cuidadoespecial . "','" . $this->suplementacionviaoral . "')")) {
            $link->commit();
            return $dev;
          } else {
            $dev->mensaje = "insert into rutinas (paciente_id, fecha, tipopiel, diahigiene1, diahigiene2,
        diacontornoojos,diabarreracutanea, diavitaminac, diaacido, diahumectante, diacuello, diaprotectorsolar,
        diamaquillaje, nochehigiene1, nochehigiene2, nochehigiene3, nochecontornoojos, nocheserum, nocheacido,
        nochehumectante, nochecuello, cuidadohigiene, cuidadohumectacion, cuidadoespecial,
        suplementacionviaoral)
        values(" . $this->paciente . ",'" . $this->fecha . "','" . $this->tipopiel . "','" . $this->diahigiene1 . "'
        ,'" . $this->diahigiene2 . "','" . $this->diacontornoojos . "','" .
            $this->diabarreracutanea . "','" . $this->diavitaminac . "','" . $this->diaacido . "','" . $this->diahumectante . "','" .
            $this->diacuello . "','" . $this->diaprotectorsolar . "','" . $this->diamaquillaje . "','" . $this->nochehigiene1 . "','" .
            $this->nochehigiene2 . "','" . $this->nochehigiene3 . "','" . $this->nochecontornoojos . "','" .
            $this->nocheserum . "','" . $this->nocheacido . "','" . $this->nochehumectante . "','" .
            $this->nochecuello . "','" . $this->cuidadohigiene . "','" . $this->cuidadohumectacion . "','" .
            $this->cuidadoespecial . "','" . $this->suplementacionviaoral . "')";
            $dev->flag = 1;
            throw new Exception("Error al registrar Rutina");
          }
        }
        else
        {
          if ($link->query("update rutinas set fecha ='". $this->fecha . "',tipopiel='" . $this->tipopiel . "',
          diahigiene1='" . $this->diahigiene1 . "',diahigiene2='" . $this->diahigiene2 . "',
          diacontornoojos='" . $this->diacontornoojos . "',diabarreracutanea='" .$this->diabarreracutanea . "',
          diavitaminac='" . $this->diavitaminac . "',diaacido='" . $this->diaacido . "',
          diahumectante='" . $this->diahumectante . "',diacuello='" .$this->diacuello . "',
          diaprotectorsolar='" . $this->diaprotectorsolar . "',diamaquillaje='" . $this->diamaquillaje . "',
          nochehigiene1='" . $this->nochehigiene1 . "',nochehigiene2='" .$this->nochehigiene2 . "',
          nochehigiene3='" . $this->nochehigiene3 . "',nochecontornoojos='" . $this->nochecontornoojos . "',
          nocheserum='" .$this->nocheserum . "',nocheacido='" . $this->nocheacido . "',
          nochehumectante='" . $this->nochehumectante . "',nochecuello='" .$this->nochecuello . "',
          cuidadohigiene='" . $this->cuidadohigiene . "',cuidadohumectacion='" . $this->cuidadohumectacion . "',
          cuidadoespecial='" .$this->cuidadoespecial . "',suplementacionviaoral='" . $this->suplementacionviaoral . "'
          where id=".$this->id)) {
            $link->commit();
            return $dev;
          } else {
            $dev->mensaje = "Error al guardar rutina";
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
