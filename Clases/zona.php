<?php
// require_once '../config.php';


/********************
*** Clase Zona  ***
*******************/
class Zona
{
  //variables
  public $id = "";
  public $nombre = "";
  public $ubicacion = "";
  public $cantidadpedidos = "";
  public $listaDias = array();
  public $listaFechas = array();
  public $fecha = "";
  public $nombreDias = array('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo');
  function __construct($id,$buscafecha=true)
  { 
      if($id!="0"){
        global $link;
        $result = $link->query("select * from zona where Id=".$id);
        $row = mysqli_fetch_array($result);
        $this->nombre = $row["Nombre"];
        $this->ubicacion = $row["Ubicacion"];
        $this->cantidadpedidos = $row["CantidadPedidos"];
        $this->id = $id;
      } else{
        $this->nombre = "Todas las zonas";
        $this->ubicacion = "";
        $this->id = $id;
      }
      if($buscafecha)
      {
        if($row["Lunes"] == "Si"){
          $this->listaDias[] = "Mon";
        }
        if($row["Martes"] == "Si"){
          $this->listaDias[] = "Tue";
        }
        if($row["Miercoles"] == "Si"){
          $this->listaDias[] = "Wed";
        }
        if($row["Jueves"] == "Si"){
          $this->listaDias[] = "Thu";
        }
        if($row["Viernes"] == "Si"){
          $this->listaDias[] = "Fri";
        }
        if($row["Sabado"] == "Si"){
          $this->listaDias[] = "Sat";
        }
        if($row["Domingo"] == "Si"){
          $this->listaDias[] = "Sun";
        }

        
        $this->fechaDisponible();
      }
  }

  function fechaDisponible()
  {
    if(count($this->listaDias)>0){
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      // $hoy = new DateTime(date('Y-m-d'));
      $flag = 0;
      $suma= 1;
      global $link;
      while ($flag<3) {
        $hoy = new DateTime(date('Y-m-d'));
        $hoy->add(new DateInterval('P'.$suma.'D'));
        $dia = $hoy->format('D');
        if (in_array($dia, $this->listaDias)) {
          $result = $link->query("select count(*) as cantidad from pedido where Zona=".$this->id."
         and FechaEntrega='". $hoy->format('Y-m-d')."'");
          $row = mysqli_fetch_array($result);
          $cantidadpedido = $row["cantidad"];
          if($cantidadpedido < $this->cantidadpedidos) {
            $flag += 1;
            $this->listaFechas[] = $hoy;
          }
          
        }
        $suma+=1;
      }
    }
    else {
      $this->fecha = "No hay fechas disponibles para su zona de entrega";
    }
     
  }


}
 ?>
