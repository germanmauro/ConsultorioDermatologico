<?php
// require_once '../config.php';
/********************
*** Clase Medico  ***
*******************/
class Medico
{
  //variables
  public $nombre = "";
  public $apellido = "";
  public $especialidad = "";
  public $id = "";

  function __construct($usuario)
  {
      global $link;
      $result = $link->query("select * from usuarios where id='".$usuario."'");
      $row = mysqli_fetch_array($result);
      $this->nombre = $row["nombre"];
      $this->apellido = $row["apellido"];
      $this->especialidad = $row["especialidad"];
      $this->id = $usuario;
  }

}
 ?>
