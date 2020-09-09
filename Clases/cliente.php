<?php
// require_once '../config.php';


/********************
*** Clase Cliente  ***
*******************/
class Cliente
{
  //variables
  public $nombre = "";
  public $apellido = "";
  public $dni = "";
  public $direccion = "";
  public $telefono = "";
  public $zona = "";
  public $user = "";

  function __construct($usuario)
  {
      global $link;
      $result = $link->query("select * from usuario where User='".$usuario."'");
      $row = mysqli_fetch_array($result);
      $this->nombre = $row["Nombre"];
      $this->apellido = $row["Apellido"];
      $this->direccion = $row["Direccion"];
      $this->telefono = $row["Telefono"];
      $this->dni = $row["DNI"];
      $this->zona = $row["Zona"];
      $this->user = $usuario;
  }


}
 ?>
