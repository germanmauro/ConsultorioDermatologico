   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/turno.php';
    $turno = new Turno();
    if (isset($_SESSION["Turno"])) {
        $turno = unserialize($_SESSION["Turno"]);
    }


    ?>

   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <h3 class="menu-text">Turno generado con exito</h3>
               <h4 class="menu-text">Clínica Dermatológica Dra. Melina Lois<br>
                   Fecha: <?= date_format(date_create($turno->fecha . ' ' . $turno->hora), "d/m/Y H:i") ?><br>
                   Paciente: <?= $turno->paciente->apellido . ', ' . $turno->paciente->nombre ?><br>
                   Duración: <?= $turno->duracion?> min<br>
                   Observaciones: <?= $turno->observaciones ?></h4>

               <!-- /.row -->

           </div>

       </div>
       <!-- /.panel -->

   </div>
   <!-- /.col-lg-8 -->