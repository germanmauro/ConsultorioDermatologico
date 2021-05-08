<?php
// require_once 'config.php';
require_once 'devolucion.php';
require_once 'medico.php';
require_once 'paciente.php';

/********************
*** Clase Turno  ***
*******************/
class Turno
{
  //variables
  public $id = "";
  public $fecha = "";
  public $hora = "";
  public $paciente = "";
  public $medico = "";
  public $duracion = "";
  public $tratamiento = "";
  public $observaciones = "";
  
  function __construct()
  {
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
      //Insertamos la venta
      if (!$link->query("INSERT INTO turnos (paciente_id,fecha,medico_id,tratamiento_id,observaciones,duracion)
        VALUES(" . $this->paciente->id . ",'" . $this->fecha .' '.$this->hora.
        "'," . $this->medico->id.",".$this->tratamiento->id.",'".$this->observaciones."',".$this->duracion.")")) {
        $dev->mensaje = "Error al registrar el turno";
        $dev->flag = 1;
        throw new Exception("Error al registrar el turno");
      }
      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }

  public function eliminar()
  {
    $dev = new Devolucion();
    global $link;
    try {
      $link->autocommit(false);

      if (!$link->query("delete from turnos where id=".$this->id)) {
        $dev->mensaje = "Error al cancelar el turno";
        $dev->flag = 1;
        throw new Exception("Error al cancelar el turno");
      }
      $link->commit(); //Insertamos todos los query

      return $dev;
    } catch (Exception $e) {
      // return  $e;
      $link->rollback();
      return $dev;
    }
  }

  function verificarHora($hora)
  {
    $horadesde = new DateTime($this->fecha . ' ' . $hora);
    $horahasta = new DateTime($this->fecha . ' ' . $hora);
    $dur = $this->duracion - 30;
    $horahasta->add(new DateInterval("PT".$dur."M"));
    global $link;
    $result = $link->query("select * from turnos where fecha between '".$horadesde->format('Y-m-d H:i')."'
    AND '". $horahasta->format('Y-m-d H:i')."' and medico_id=".$this->medico->id);
    if(mysqli_num_rows($result) == 0)
    {
      $horahasta = new DateTime($this->fecha . ' ' . $hora);
      $horahasta->add(new DateInterval("PT" . $this->duracion . "M"));
      $dia = date_format(date_create($this->fecha), "w");
      $horarios = $link->query("select * 
                                    from dias where medico_id=" . $this->medico->id . " and
                                    dia=" . $dia . " and CONVERT(horadesde,TIME)<=CONVERT('".$horadesde->format('H:i')."',TIME)
                                  and CONVERT(horahasta,TIME)>=CONVERT('".$horahasta->format('H:i')."',TIME)");
      if(mysqli_num_rows($horarios) > 0)
      {
        $horahasta = new DateTime($this->fecha . ' ' . $hora);
      $horahasta->add(new DateInterval("PT" . $dur . "M"));
        $bloqueos = $link->query("select * 
                                    from bloqueosparciales where medico_id=" . $this->medico->id . " and
                                    dia='" . $this->fecha . "' and CONVERT(horadesde,TIME)between '" . $horadesde->format('H:i') . "'
                                  and '" . $horahasta->format('H:i') . "'");
        if (mysqli_num_rows($bloqueos) == 0) {
          return 0;
        } else {
          return 3;
        }
      } else {
        return 2;
      }
    } else {
      return 1;
    }
  }
  
}
class TurnoListado
{
  public $listaTurnos = array();
  function __construct($medico,$desde,$hasta)
  {
    global $link;
    $result = $link->query("select fecha,IFNULL(observaciones,' S')as observaciones,duracion,
                     CONCAT(pacientes.apellido,', ',pacientes.nombre) as paciente,
                     CONCAT(tratamientos.codigo,' - ',tratamientos.denominacion) as tratamiento,
                     CONCAT(usuarios.apellido,', ',usuarios.nombre) as medico
                     FROM turnos
                     join usuarios on usuarios.id = turnos.medico_id
                     join pacientes on pacientes.id = turnos.paciente_id
                     join tratamientos on tratamientos.id = turnos.tratamiento_id
                     where (usuarios.id =".$medico." or 0 = ".$medico.")
                     and turnos.fecha between '".$desde. " 00:00:00' and '".$hasta." 23:59:59'
                     order by medico,fecha");
    while ($row = mysqli_fetch_array($result)) {
      $turno = new Turno();
      $turno->fecha = $row["fecha"];
      $turno->paciente = $row["paciente"];
      $turno->duracion = $row["duracion"];
      $turno->medico = $row["medico"];
      $turno->tratamiento = $row["tratamiento"];
      $turno->observaciones = $row["observaciones"];
      $this->listaTurnos[] = $turno;
    }
  }
}

class Tratamiento
{
  public $id = "";
  public $codigo = "";
  public $denominacion = "";
  
  function __construct($id)
  {
    global $link;
    $result = $link->query("select * from tratamientos where id=".$id);
    $row = mysqli_fetch_array($result);
    $this->id = $id;
    $this->codigo = $row["codigo"];
    $this->denominacion = $row["denominacion"];
  }
}
 ?>
