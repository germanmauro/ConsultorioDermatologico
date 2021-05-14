<?php
// require_once 'config.php';
require_once 'devolucion.php';
require_once 'medico.php';
require_once 'paciente.php';
require_once 'formapago.php';

/********************
*** Clase Venta  ***
*******************/
class Venta
{
  //variables
  public $id = "";
  public $fecha = "";
  public $subtotal = "";
  public $interes = "";
  public $descuento = "";
  public $total = "";
  public $medico = "";
  public $formapago = "";
  public $factura = ""; //Numero de factura
  public $observaciones = "";
  
  public $precioProductoIgual = false;
  public $precioTratamientoIgual = false;

  
  public $listaProductos = array();
  public $listaProductosAnterior = array();//Para matener Ok el stock

  public $listaTratamientos = array();

  function __construct()
  {
    // $this->fecha = date_format(date_create(),'Y-m-d');
  }

  public function agregarPaciente($codigo,$nombre, $apellido,$dni, $obrasocial,
   $carnet, $telefono, $email, $direccion, $localidad,$fechanacimiento, $profesion, $referido)
  {
    $paciente = new Paciente();
    $paciente->codigo = $codigo;
    $paciente->nombre = $nombre;
    $paciente->apellido = $apellido;
    $paciente->dni = $dni;
    $paciente->obrasocial = $obrasocial;
    $paciente->carnet = $carnet;
    $paciente->telefono = $telefono;
    $paciente->email = $email;
    $paciente->direccion = $direccion;
    $paciente->localidad = $localidad;
    $paciente->fechanacimiento = $fechanacimiento;
    $paciente->profesion = $profesion;
    $paciente->referido = $referido;
    $this->paciente = $paciente;
  }

  public function guardar()
  {
    $dev = new Devolucion();
    global $link;
    try {
      $link->autocommit(false);
      $this->paciente->guardar();
      //Si hay tratamientos cargo la comision
      if(count($this->listaTratamientos)>0)
      {
        $this->calcularComisiones();
      }
      //Insertamos la venta
      if($this->id == "")
      {
        if (!$link->query("INSERT INTO ventas (paciente_id,fecha,formapago_id,porcentajeproducto,porcentajetratamiento,factura,observaciones,total)
          VALUES(" . $this->paciente->id . ",'" . $this->fecha . "'," . $this->formapago->id . ",
          '" . $this->formapago->porcentajeproducto . "',
          '" . $this->formapago->porcentajetratamiento . "',
          '" . $this->factura . "','".$this->observaciones."',
          '" . $this->total . "')")) {
          $dev->mensaje = "Error al registrar la venta";
          $dev->flag = 1;
          throw new Exception("Error al registrar la venta");
        }
        $this->id = $link->insert_id;
      }
      else
      {
        //Actualizar
        if (!$link->query("update ventas set paciente_id=".$this->paciente->id.",fecha='".$this->fecha."',
        formapago_id=".$this->formapago->id.",porcentajeproducto='".$this->formapago->porcentajeproducto."',
        porcentajetratamiento='".$this->formapago->porcentajetratamiento."',factura='".$this->factura."',
        total='".$this->total."',observaciones='".$this->observaciones."' where id=" . $this->id)) {
          $dev->mensaje = "Error al registrar la venta";
          $dev->flag = 1;
          throw new Exception("Error al registrar la venta");
        }
        //Borrar detalle
        if (!$link->query("delete from ventasproductos where venta_id=".$this->id))
        {
          $dev->mensaje = "Error al registrar la venta";
          $dev->flag = 1;
          throw new Exception("Error al registrar la venta");
        }
        if (!$link->query("delete from ventastratamientos where venta_id=".$this->id))
        {
          $dev->mensaje = "Error al registrar la venta";
          $dev->flag = 1;
          throw new Exception("Error al registrar la venta");
        }
        //Vuelvo a sumar los productos
        foreach ($this->listaProductosAnterior as $det) {
          //actualizo stock
          if(!$link->query("update productos set 
          stock = stock +" . $det->cantidad. " where id=".$det->id))
          {
            $dev->flag = 1;
            $dev->mensaje = "Error al registrar producto";
            throw new Exception("Error al registrar la venta");
          }  
        }
      }

      foreach ($this->listaProductos as $det) {
        //Insertamos el detalle de la venta
        $query = "INSERT INTO ventasproductos (venta_id,producto_id,cantidad,preciounitario,preciolista,total)
          VALUES(" . $this->id . "," . $det->id . "," . $det->cantidad . ",
          '" . $det->precioUnitario . "','".$det->precioLista."','".$det->total."')";

        if (!$link->query($query)) {
          $dev->flag = 1;
          $dev->mensaje = "Error al registrar producto";
          throw new Exception($query);
        } else {
          //actualizo stock
          if(!$link->query("update productos set 
          stock = stock -" . $det->cantidad. " where id=".$det->id))
          {
            $dev->flag = 1;
            $dev->mensaje = "Error al registrar producto";
            throw new Exception("Error al registrar la venta");
          }
        }
      }
      foreach ($this->listaTratamientos as $det) {
        //Insertamos el detalle de la venta
        $query = "INSERT INTO ventastratamientos (venta_id,tratamiento_id,medico_id,preciounitario,preciolista,
        cantidad,total,porcentaje,comision)
          VALUES(" . $this->id . "," . $det->id . "," . $this->medico->id . ",
          '" . $det->precioUnitario . "','".$det->precioLista."',".$det->cantidad.",'".$det->total."',
          '".$det->porcentaje."','".$det->comision."')";

        if (!$link->query($query)) {
          $dev->flag = 1;
          $dev->mensaje = $query;
          // $dev->mensaje = "Error al registrar producto";
          throw new Exception($query);
        }
      }
      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }

  public function cargaVenta($id)
  {
    $dev = new Devolucion();
    global $link;
    $result = $link->query("select * from ventas where id=".$id);
    $row = mysqli_fetch_array($result);
    $this->id = $id;
    $this->fecha = $row["fecha"];
    $this->total = $row["total"];
    $this->factura = $row["factura"];
    $this->observaciones = $row["observaciones"];
    $this->paciente = new Paciente();
    $this->paciente->cargar($row["paciente_id"]);
    $this->formapago = new FormaPago($row["formapago_id"]);
    $this->formapago->porcentajetratamiento = $row["porcentajetratamiento"];
    $this->formapago->porcentajeproducto = $row["porcentajeproducto"];

    $resultDetalle = $link->query("select * from ventasproductos where venta_id=".$id);
    while ($item = mysqli_fetch_array($resultDetalle)) {
      $prod = new Producto($item["producto_id"]);
      $prod->precioUnitario = $item["preciounitario"];
      $prod->precioLista = $item["preciolista"];
      $prod->total = $item["total"];
      $prod->cantidad = $item["cantidad"];
      $this->listaProductos[] = $prod;
    }
    $this->listaProductosAnterior = $this->listaProductos;

    $resultDetalle = $link->query("select * from ventastratamientos where venta_id=".$id);
    while ($item = mysqli_fetch_array($resultDetalle)) {
      $prod = new Tratamiento($item["tratamiento_id"]);
      $prod->precioUnitario = $item["preciounitario"];
      $prod->precioLista = $item["preciolista"];
      $prod->total = $item["total"];
      $prod->cantidad = $item["cantidad"];
      $this->listaTratamientos[] = $prod;
    }
  }
  public function eliminar()
  {
    $dev = new Devolucion();
    global $link;
    try {
      $link->autocommit(false);
      foreach ($this->listaProductos as $det) {
          //actualizo stock
          if (!$link->query("update productos set 
          stock = stock +" . $det->cantidad . " where id=" . $det->id)) {
            $dev->flag = 1;
            $dev->mensaje = "Error al registrar producto";
            throw new Exception("Error al registrar la venta");
          }
      }
      if (!$link->query("delete from ventas where id=".$this->id)) {
        $dev->mensaje = "Error al eliminar venta";
        $dev->flag = 1;
        throw new Exception("Error al eliminar venta");
      }
      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }

  function calcularTotal()
  {
    $this->total = 0;
    $this->descuento = 0;
    $this->interes = 0;
    if($this->precioProductoIgual) {
      $porcentajeproducto = 1;
    } else {
      $porcentajeproducto = $this->formapago->porcentajeproducto;
    }

    if($this->precioTratamientoIgual) {
      $porcentajetratamiento = 1;
    } else {
      $porcentajetratamiento = $this->formapago->porcentajetratamiento;
    }
    
    //productos
    foreach ($this->listaProductos as $item) {
      if ($item->fijo) {
        $item->precioUnitario = $item->precioLista;
      } else {
        $item->precioUnitario = round($item->precioLista * $porcentajeproducto);
      }
      
      $item->total = round($item->precioUnitario * $item->cantidad);
      $this->total += $item->total;
    }
    //tratamiento
    foreach ($this->listaTratamientos as $item) {
      if ($item->fijo) {
        $item->precioUnitario = $item->precioLista;
      } else {
        $item->precioUnitario = round($item->precioLista * $porcentajetratamiento);
      }
      $item->total = round($item->precioUnitario * $item->cantidad);
      $this->total += $item->total;
    }
    $this->total = round($this->total);
  }

  function calcularTotalSinFP()
  {
    $this->total = 0;
    $this->descuento = 0;
    $this->interes = 0;
    //productos
    foreach ($this->listaProductos as $item) {
      $item->precioUnitario = round($item->precioLista);
      $item->total = round($item->precioUnitario * $item->cantidad);
      $this->total += $item->total;
    }
    //tratamiento
    foreach ($this->listaTratamientos as $item) {
      $item->precioUnitario = round($item->precioLista);
      $item->total = round($item->precioUnitario * $item->cantidad);
      $this->total += $item->total;
    }
    $this->total = round($this->total);
  }

  function calcularComisiones()
  {
    global $link;
    $result = $link->query("select porcentajetratamiento from formaspago where nombre='Efectivo'");
    $row = mysqli_fetch_array($result);
    $porcentajeEfectivo = $row["porcentajetratamiento"];
    //tratamiento
    foreach ($this->listaTratamientos as $item) {
      $item->comision = round($item->precioLista * $item->cantidad * $porcentajeEfectivo * $item->porcentaje /10000);
    }
  }

  //PRODUCTOS
  function agregarProducto($id,$cantidad)
  {
    $flag = true;
    foreach ($this->listaProductos as $item) {
      if($item->id == $id)
      {
        $item->cantidad += $cantidad;
        $flag = false;
        break;
      }
    }
    if($flag)
    {
      $prod = new Producto($id);
      $prod->cantidad = $cantidad;
      $this->listaProductos[] = $prod;
    }
    $this->calcularTotalSinFP();  
  }

  function eliminarProducto($id)
  {
    $lista = array();
    foreach ($this->listaProductos as $item) {
      if($item->id != $id)
      {
        $lista[] = $item;
      }
    }
    $this->listaProductos = $lista;
  }

  //TRATAMIENTOS
  function agregarTratamiento($id, $cantidad)
  {
    $flag = true;
    foreach ($this->listaTratamientos as $item) {
      if ($item->id == $id
      ) {
        $item->cantidad += $cantidad;
        $flag = false;
        break;
      }
    }
    if ($flag) {
      $prod = new Tratamiento($id);
      $prod->cantidad = $cantidad;
      $this->listaTratamientos[] = $prod;
    }
    $this->calcularTotalSinFP();
  }

  function eliminarTratamiento($id)
  {
    foreach ($this->listaTratamientos as  $key=>$item) {
      if($item->id == $id)
      {
        unset($this->listaTratamientos[$key]);
        break;
      }
    }
  }


}

class Producto
{
  public $id = "";
  public $codigo = "";
  public $denominacion = "";
  public $marca = "";
  public $precioLista = "";
  public $precioUnitario = "";
  public $total = "";
  public $cantidad = 0;
  public $fijo = "";
  
  function __construct($id)
  {
    global $link;
    $result = $link->query("select * from productos where id=".$id);
    $row = mysqli_fetch_array($result);
    $this->id = $id;
    $this->codigo = $row["codigo"];
    $this->denominacion = $row["denominacion"];
    $this->marca = $row["marca"];
    $this->precioLista = $row["precioventa"];
    $this->fijo = $row["fijo"];
  }
}

class Tratamiento
{
  public $id = "";
  public $codigo = "";
  public $denominacion = "";
  public $cantidad = "";
  public $medico_id = "";
  public $precioUnitario = "";
  public $total = "";
  public $precioLista = "";
  public $porcentaje = "";
  public $comision = "";
  public $fijo = "";
  
  function __construct($id)
  {
    global $link;
    $result = $link->query("select * from tratamientos where id=".$id);
    $row = mysqli_fetch_array($result);
    $this->id = $id;
    $this->codigo = $row["codigo"];
    $this->denominacion = $row["denominacion"];
    $this->porcentaje = $row["porcentajemedico"];
    $this->precioLista = $row["precioventa"];
    $this->fijo = $row["fijo"];
  }
}
 ?>
