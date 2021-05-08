<?php
// require_once '../config.php';
require_once 'devolucion.php';

/********************
*** Clase Historia  ***
*******************/
class Historia
{
  //variables
    public $neurologico = "";
    public $cardiovascular = "";
    public $endocrinologico = "";
    public $pulmonar = "";
    public $digestivo = "";
    public $renal = "";
    public $dermatologico = "";
    public $hematologicas = "";
    public $antecedentesotros = "";

    public $antigangrenantes = "";
    public $anticoagulantes = "";
    public $analgesicos = "";
    public $suplementosvitaminicos = "";
    public $antidepresivos = "";
    public $medicamentosotros = "";
    
    public $alergiafarmaco = "";
    public $alergiaanestesicolocal = "";

    public $tabaco = "";
    public $alcohol = "";
    public $actividadfisica = "";
    public $exposicionsolar = "";
    
    public $toxinabotulinica = "";
    public $acidohialuronico = "";
    public $antecedentesquirurgicos = "";
    
    public $antecedentestraumaticos = "";
    public $cicatrizacion = "";
    public $reaccionesvagales = "";
    public $dismorfofobia = "";
    public $vacunacionantitetanica = "";
    public $fragilidadcapilar = "";
    public $tratamientoodontologico = "";
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
    from historias
    where paciente_id = ".$id);
    if(mysqli_num_rows($result)>0)
    {
      $row = mysqli_fetch_array($result);
      $this->neurologico = $row["neurologico"];
      $this->cardiovascular = $row["cardiovascular"];
      $this->endocrinologico = $row["endocrinologico"];
      $this->pulmonar = $row["pulmonar"];
      $this->digestivo = $row["digestivo"];
      $this->renal = $row["renal"];
      $this->dermatologico = $row["dermatologico"];
      $this->hematologicas = $row["hematologicas"];
      $this->antecedentesotros = $row["antecedentesotros"];

      $this->antigangrenantes = $row["antigangrenantes"];
      $this->anticoagulantes = $row["anticoagulantes"];
      $this->analgesicos = $row["analgesicos"];
      $this->suplementosvitaminicos = $row["suplementosvitaminicos"];
      $this->antidepresivos = $row["antidepresivos"];
      $this->medicamentosotros = $row["medicamentosotros"];

      $this->alergiafarmaco = $row["alergiafarmaco"];
      $this->alergiaanestesicolocal = $row["alergiaanestesicolocal"];

      $this->tabaco = $row["tabaco"];
      $this->alcohol = $row["alcohol"];
      $this->actividadfisica = $row["actividadfisica"];
      $this->exposicionsolar = $row["exposicionsolar"];

      $this->toxinabotulinica = $row["toxinabotulinica"];
      $this->acidohialuronico = $row["acidohialuronico"];
      $this->antecedentesquirurgicos = $row["antecedentesquirurgicos"];

      $this->antecedentestraumaticos = $row["antecedentestraumaticos"];
      $this->cicatrizacion = $row["cicatrizacion"];
      $this->reaccionesvagales = $row["reaccionesvagales"];
      $this->dismorfofobia = $row["dismorfofobia"];
      $this->vacunacionantitetanica = $row["vacunacionantitetanica"];
      $this->fragilidadcapilar = $row["fragilidadcapilar"];
      $this->tratamientoodontologico = $row["tratamientoodontologico"];
    }
    
  }
  function guardar()
  {
    global $link;
    $dev = new Devolucion();
    $link->autocommit(false);
    try {
      if (!$link->query("delete from historias where paciente_id =" . $this->paciente)) {
        $dev->mensaje = "Error al registrar HC";
        $dev->flag = 1;
        throw new Exception("Error al registrar HC");
      } else {
        if ($link->query("insert into historias (paciente_id, neurologico, cardiovascular, endocrinologico, pulmonar, digestivo,
       renal,dermatologico, hematologicas, antecedentesotros, antigangrenantes, anticoagulantes, analgesicos,
      suplementosvitaminicos, antidepresivos, medicamentosotros,alergiafarmaco, alergiaanestesicolocal,
      tabaco, alcohol, actividadfisica, exposicionsolar, toxinabotulinica, acidohialuronico,
      antecedentesquirurgicos, antecedentestraumaticos, cicatrizacion, reaccionesvagales,
      dismorfofobia, vacunacionantitetanica, fragilidadcapilar, tratamientoodontologico)
      values(" . $this->paciente . ",'" . $this->neurologico . "','" . $this->cardiovascular . "','" . $this->endocrinologico . "','" . $this->pulmonar . "','" . $this->digestivo . "','" .
        $this->renal . "','" . $this->dermatologico . "','" . $this->hematologicas . "','" . $this->antecedentesotros . "','" .
        $this->antigangrenantes . "','" . $this->anticoagulantes . "','" . $this->analgesicos . "','" . $this->suplementosvitaminicos . "','" .
        $this->antidepresivos . "','" . $this->medicamentosotros . "','" .
        $this->alergiafarmaco . "','" .  $this->alergiaanestesicolocal . "','" .
        $this->tabaco . "','" . $this->alcohol . "','" . $this->actividadfisica . "','" . $this->exposicionsolar . "','" .
        $this->toxinabotulinica . "','" . $this->acidohialuronico . "','" . $this->antecedentesquirurgicos . "','" .
        $this->antecedentestraumaticos . "','" . $this->cicatrizacion . "','" . $this->reaccionesvagales . "','" .
        $this->dismorfofobia . "','" . $this->vacunacionantitetanica . "','" . $this->fragilidadcapilar . "','" .
        $this->tratamientoodontologico . "')")) {
          $link->commit();
          return $dev;
        } else {
          $dev->mensaje = "Error al registrar HC";
          $dev->flag = 1;
          throw new Exception("Error al registrar HC");
        }
      }
    } catch (\Throwable $th) {
      $link->rollback();
      return $dev;
    }    
  }

}
 ?>
