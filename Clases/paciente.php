<?php
require_once 'historia.php';
require_once 'rutina.php';


/********************
*** Clase Paciente  ***
*******************/
class Paciente
{
  //variables
  public $codigo = "";
  public $nombre = "";
  public $apellido = "";
  public $dni = "";
  public $obrasocial = "";
  public $carnet = "";
  public $email = "";
  public $telefono = "";
  public $direccion = "";
  public $localidad = "";
  public $fechanacimiento = "";
  public $referido = "";
  public $profesion = "";
  public $alta = "";
  public $id = "";
  public $historia = "";
  public $rutina = "";

  function cargar($id)
  {
    global $link;
    $result = $link->query("select pacientes.*,obrassociales.nombre as obrasocial 
    from pacientes
    join obrassociales on obrassociales.id = pacientes.obrasocial_id
    where pacientes.id=".$id);
    $row = mysqli_fetch_array($result);
    $this->codigo = $row["codigo"];
    $this->nombre = $row["nombre"];
    $this->apellido = $row["apellido"];
    $this->dni = $row["dni"];
    $this->obrasocial = $row["obrasocial"];
    $this->carnet = $row["carnet"];
    $this->email = $row["email"];
    $this->telefono = $row["telefono"];
    $this->direccion = $row["direccion"];
    $this->localidad = $row["localidad"];
    $this->fechanacimiento = $row["fechanacimiento"];
    $this->referido = $row["referido"];
    $this->profesion = $row["profesion"];
    $this->alta = $row["alta"];
    $this->id = $id;
    $this->historia = new Historia($id);
    $this->rutina = new Rutina($id);
  }

  function guardar()
  {
    global $link;
    $link->autocommit(false);
    if ($this->codigo == "") {
      $result = $link->query("select max(codigo)+1 as codigo from pacientes where baja = 'False'");
      $row = mysqli_fetch_array($result);
      $this->codigo = $row["codigo"];
    }
    $result = $link->query("select * from pacientes where codigo=" . $this->codigo . " and baja='False'");
    if (mysqli_num_rows($result) == 0) {
      $hoy = new DateTime();
      $link->query("insert into pacientes (codigo,apellido,nombre,dni,obrasocial_id,
    carnet,telefono, email,direccion,localidad, fechanacimiento, profesion, referido,alta)
      values('" . $this->codigo . "','".$this->apellido."','" . $this->nombre . "','" . $this->dni . 
      "',".$this->obrasocial.",'" . $this->carnet . "','" . $this->telefono . "','" . $this->email . 
      "','".$this->direccion."','".$this->localidad."','".$this->fechanacimiento."','".$this->profesion.
      "','".$this->referido."','".$hoy->format('Y-m-d')."')");
      
      $this->id = $link->insert_id;
    } else {
      $row = mysqli_fetch_array($result);
      $this->id = $row["id"];
      $link->query("update pacientes set nombre='".$this->nombre."',apellido='".$this->apellido."',
      dni='".$this->dni."', obrasocial_id=" . $this->obrasocial .",carnet='".$this->carnet."',
      telefono='" . $this->telefono ."',direccion='".$this->direccion."',localidad='".$this->localidad."',
      fechanacimiento='".$this->fechanacimiento."',profesion='".$this->profesion."',referido='".$this->referido.
        "' where id=".$this->id);
    }
  }

  function registrarConsulta($fecha,$motivo,$detalle)
  {
    global $link;
    $link->query("insert into consultas (fecha,motivo,detalle,paciente_id)
    values ('".$fecha."','".$motivo."','".$detalle."',".$this->id.")");
  }

  function eliminarConsulta($id)
  {
    global $link;
    $link->query("delete from consultas where id=".$id);
  }

  function registrarArchivo($fecha,$descripcion,$archivo)
  {
    global $link;
    $link->query("insert into archivos (fecha,descripcion,archivo,paciente_id)
    values ('".$fecha."','".$descripcion."','".$archivo."',".$this->id.")");
  }

  function eliminarArchivo($id)
  {
    global $link;
    $link->query("delete from archivos where id=".$id);
  }

}
 ?>
