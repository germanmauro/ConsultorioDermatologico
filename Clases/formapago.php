<?php
// require_once '../config.php';
/********************
*** Clase FormaPago  ***
*******************/
class FormaPago
{
  //variables
  public $nombre = "";
  public $porcentajeproducto = "";
  public $porcentajetratamiento = "";
  public $id = "";

  function __construct($id)
  {
      global $link;
      $result = $link->query("select * from formaspago where id='".$id."'");
      $row = mysqli_fetch_array($result);
      $this->nombre = $row["nombre"];
      $this->porcentajeproducto = $row["porcentajeproducto"]/100;
      $this->porcentajetratamiento = $row["porcentajetratamiento"]/100;
      $this->id = $id;
  }

}
 ?>
