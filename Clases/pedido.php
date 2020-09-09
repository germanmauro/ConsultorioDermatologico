<?php
// require_once 'config.php';
require_once 'devolucion.php';
require_once 'cliente.php';
require_once 'zona.php';

/********************
*** Clase Pedido  ***
*******************/
class Pedido
{
  //variables
  public $id = 0;
  public $numero = 0;
  //Esto es para identificar la lista de items.
  public $itemid = 0;
  public $estado = "Generado";
  public $fecha = "";
  public $fechaentrega = "";
  public $tipo = "X";//Por ahora no hay facutra A y B
  public $cliente = "";
  public $zona = "";
  public $telefono = "";
  public $direccion = "";
  public $pago = "";
  public $formapago = "";
  public $entregado = "No";
  public $comentarios = "";
  public $total = 0.00;
  public $entregamodificacion = "";
  function __construct()
  {
  }
  //Detalle del pedido
  public $_colDetalle = array();

   public function agregarProducto($detalle,$cantidad)
   {
      $det = new PedidoDetalle();
      $det->cargar($detalle);
      $flag = false;

      foreach ($this->_colDetalle as $item) {
        if($det->producto == $item->producto)
        {
          $item->cantidad += $cantidad;
          $item->total = $item->cantidad * $item->precio;
          $flag = true;
          break;
        }
      }
      
      if(!$flag){
        $det->cantidad = $cantidad;
        $det->total = $det->cantidad * $det->precio;
        $this->_colDetalle[] = $det;
      }
   }

  public function deleteItem($id)
  {
    $col = array();
    foreach ($this->_colDetalle as $item) {
      if ($item->producto != $id) {
        $col[] = $item;
      }
    }
    $this->_colDetalle = $col;
  }
  

   public function calcularTotal()
   {
      $this->total = 0.00;
      foreach ($this->_colDetalle as $det) {
        $this->total+=$det->total;
      }
   }

   function cargarCliente($usuario)
   {
     $cliente = new Cliente($usuario);
     $this->cliente = $cliente;
   }
   
   //Agregar datos de entrega
   function agregarEntrega($zona,$direccion,$telefono,$formapago,$comentarios)
   {
     $this->zona = new Zona($zona);
     $this->direccion = $direccion;
     $this->telefono = $telefono;
     $this->formapago = $formapago;
     $this->comentarios = $comentarios;
   }

   //Guardamos la venta con transacción para evitar inconsistencia.
  function guardar($fecha)
  {
    //Defino la clase para saber la devolcion del la factura
    $dev = new Devolucion();
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $this->fecha = date('Y-m-d H:i:s');
    global $link;

    try {
      $link->autocommit(false);
      //Obtengo el numero de venta
      $result = $link->query("select ifnull(max(id)+1,1) as Id from pedido");
      $row = mysqli_fetch_array($result);
      $this->numero = $row["Id"];
      $dev->valor = $this->numero;
      $this->calcularTotal();
      //Insertamos la venta
      if(!$link->query("INSERT INTO pedido (Id,Fecha,Direccion,Telefono,FechaEntrega,Zona,Estado,Cliente,Total,FormaPago,Comentarios)
        VALUES(".$this->numero.",'".$this->fecha."','".$this->direccion."','".$this->telefono."','".$fecha."',".$this->zona->id.",
          '".$this->estado."','".$this->cliente->user."','".$this->total."','".$this->formapago . "','" . $this->comentarios . "')"))
      {
        $dev->mensaje = "Error al registrar el pedido";
        $dev->flag=1;
        throw new Exception("Error al registrar el pedido");
      }

      foreach ($this->_colDetalle as $det) {
        $det->total = $det->precio * $det->cantidad;
        //Insertamos el detalle de la venta
        $query = "INSERT INTO pedidodetalle (Pedido,Cantidad,Precio,Producto,Total,Nombre)
          VALUES(".$this->numero.",".$det->cantidad.",'".$det->precio."','".$det->producto."','".$det->total."','".$det->nombre."')";

        if(!$link->query($query))
        {
          $dev->flag=1;
          $dev->mensaje = "Error al registrar la detalle del pedido";
          throw new Exception($query);
        }

     
        if(!$link->query("UPDATE producto set Stock = Stock -".$det->cantidad." where Id =".$det->producto))
        {
          $dev->flag=1;
          $dev->mensaje = "Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad";
          throw new Exception("Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad");
        }//SIN CONTROL DE STOCK - SOLO LO DESCUENTA
        // else {
        //   $result = $link->query("select Cantidad from producto where Id=".$det->producto);
        //   $row = mysqli_fetch_array($result);
        //   $cantidad = $row["Cantidad"];
        //   if($cantidad<0)
        //   {
        //     $dev->flag=1;
        //     $dev->mensaje = "Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad";
        //     throw new Exception("Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad");
        //   }
        // }

          
        }
      

      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }

   //Guardamos la venta con transacción para evitar inconsistencia.
  function modificarPedido()
  {
    $dev = new Devolucion();
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $this->fecha = date('Y-m-d H:i:s');
    global $link;

    try {
      $link->autocommit(false);
      
      $dev->valor = $this->numero;
      $this->calcularTotal();
      //Insertamos la venta
      if(!$link->query("UPDATE pedido SET Total ='" . $this->total . "' where Id=" . $this->numero))
      {
        $dev->mensaje = $query;
        $dev->flag=1;
        throw new Exception("Error al registrar el pedido");
      }
      if (!$link->query("DELETE from pedidodetalle where Pedido=".$this->numero))
       {
        $dev->mensaje = "Error al registrar el pedido";
        $dev->flag = 1;
        throw new Exception("Error al registrar el pedido");
      }
      foreach ($this->_colDetalle as $det) {
        $det->total = $det->precio * $det->cantidad;
        //Insertamos el detalle de la venta
        $query = "INSERT INTO pedidodetalle (Pedido,Cantidad,Precio,Producto,Total,Nombre)
          VALUES(".$this->numero.",".$det->cantidad.",'".$det->precio."','".$det->producto."','".$det->total."','".$det->nombre."')";

        if(!$link->query($query))
        {
          $dev->flag=1;
          $dev->mensaje = "Error al registrar la detalle del pedido";
          throw new Exception($query);
        }

     
        if(!$link->query("UPDATE producto set Stock = Stock -".$det->cantidad." where Id =".$det->producto))
        {
          $dev->flag=1;
          $dev->mensaje = "Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad";
          throw new Exception("Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad");
        }
        //SIN CONTROL DE STOCK - SOLO LO DESCUENTA
        // else {
        //   $result = $link->query("select Cantidad from producto where Id=".$det->producto);
        //   $row = mysqli_fetch_array($result);
        //   $cantidad = $row["Cantidad"];
        //   if($cantidad<0)
        //   {
        //     $dev->flag=1;
        //     $dev->mensaje = "Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad";
        //     throw new Exception("Error al actualizar stock del producto ".$det->nombre." - Verifique disponibilidad");
        //   }
        // }

          
        }
      

      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }
  
  function cargar($num)
  {
    global $link;
    $this->numero = $num;
    $result = $link->query("select pedido.Cliente as Cliente,pedido.Total as Total,
    DATE_FORMAT(pedido.Fecha,'%d/%m/%y') as Fecha,DATE_FORMAT(pedido.FechaEntrega,'%d/%m/%y') as FechaEntrega,
    pedido.Direccion as Direccion,pedido.Telefono as Telefono,pedido.zona as Zona ,FormaPago,Comentarios,FechaEntrega as EntregaModificacion
    from pedido 
    where pedido.Id=".$num);

    $row = mysqli_fetch_array($result);

    $this->total = $row["Total"];
    $this->fecha = $row["Fecha"];
    $this->fechaentrega = $row["FechaEntrega"];
    $this->entregamodificacion = $row["EntregaModificacion"];
    $this->direccion = $row["Direccion"];
    $this->formapago = $row["FormaPago"];
    $this->comentarios = $row["Comentarios"];
    $this->telefono = $row["Telefono"];
    $this->zona = new Zona($row["Zona"],false);
    $this->cliente = new Cliente($row["Cliente"]);

    $result = $link->query("select Id from pedidodetalle where Pedido=".$num." order by Nombre");
    while ($row = mysqli_fetch_array($result)) {
      $detalle = new PedidoDetalle();
      $detalle->cargaDetalle($row["Id"]);
      $this->_colDetalle [] = $detalle;
    }
  }
 
}

/**
 * Pedido detalle
 */
class PedidoDetalle
{
  public $id = "";
  public $producto = "";
  public $codigo = "";
  public $nombre = "";
  public $tipo = "";
  public $precio = 0.00;
  public $cantidad = 0.00;
  public $total = 0.00;
  public $imagen = "";

  //Detalle del producto
  public $_colDetalle = array();

  function __construct()
  {
  }
  
  function cargar($pro)
  {

    $this->producto = $pro;
    global $link;
    $sql = "SELECT * from producto where Id =".$pro;
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $this->nombre = $row["Nombre"];
                $this->precio = $row["Precio"];
                $this->codigo = $row["Codigo"];
                $this->tipo = $row["Tipo"];
                $this->imagen = $row["Imagen"];
  
            // Free result set
            mysqli_free_result($result);
        }
    }
  }

  function cargaDetalle($id)
  {
    global $link;
    $sql = "SELECT producto.Id as Id,producto.Nombre as Nombre,pedidodetalle.Precio as Precio,producto.Codigo as Codigo,
     pedidodetalle.Cantidad as Cantidad, pedidodetalle.Total as Total, producto.Tipo as Tipo,producto.Imagen as Imagen
     from pedidodetalle
     join producto on producto.Id = pedidodetalle.Producto
     where pedidodetalle.Id =".$id;
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $this->nombre = $row["Nombre"];
                $this->codigo = $row["Codigo"];
                $this->precio = $row["Precio"];
                $this->cantidad = $row["Cantidad"];
                $this->tipo = $row["Tipo"];
                $this->total = $row["Total"];
                $this->producto = $row["Id"];
                $this->imagen = $row["Imagen"];
            // Free result set
            mysqli_free_result($result);
        }
    }
  }

}

/****************************
*** Clase Pedido Listado  ***
****************************/
class PedidoListado
{
  //variables
  public $fecha = "";
  public $zona = "";
  //Lista de pedidos
  public $_listaPedidos = array();
  function __construct($zona,$fecha)
  {
    $this->fecha = $fecha;
    $this->zona = $zona;
    $this->cargarPedidos();
  }

  function cargarPedidos()
  {
    $lista = array();
    global $link;
    $result = $link->query("select Id from pedido where Estado='Generado' and FechaEntrega='".$this->fecha."' 
    and (Zona=".$this->zona." or '0'='".$this->zona."')");
    while ($row = mysqli_fetch_array($result)) {
      $lista [] = $row["Id"];
    }
    foreach ($lista as $item) {
      $pedido = new Pedido();
      $pedido->cargar($item);
      $this->_listaPedidos[] = $pedido;
    }
  }
}

/****************************
*** Clase Pedido Reporte  ***
****************************/
class PedidoReporte
{
  //variables
  public $fecha = "";
  public $zona = "";
  //Lista de pedidos
  public $_listaPedidos = array();
  function __construct($zona,$fecha)
  {
    $this->fecha = $fecha;
    $this->zona = new Zona($zona,false);
    $this->cargarReporte();
  }

  function cargarReporte()
  {
    global $link;
    $result = $link->query("select Producto,SUM(Cantidad) as Cantidad,SUM(pedidodetalle.Total) as Total
    from pedido
    join pedidodetalle on pedidodetalle.Pedido = pedido.Id
    where Estado='Generado' AND FechaEntrega='" . $this->fecha . "' 
    and (Zona=" . $this->zona->id . " or '0'='" . $this->zona->id . "')
    group by Producto
    order by pedidodetalle.Nombre");
    while ($row = mysqli_fetch_array($result)) {
      $det = new PedidoDetalle();
      $det->cargar($row["Producto"]);
      $det->cantidad = $row["Cantidad"];
      $det->total = $row["Total"];
      $this->_listaPedidos [] = $det;
    }

  }
}
 ?>
