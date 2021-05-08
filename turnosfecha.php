   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/turno.php';
    $turno = new Turno();
    if (isset($_SESSION["Turno"])) {
        $turno = unserialize($_SESSION["Turno"]);
    }
    if ($turno->fecha == "") {
        $fecha = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));
        $turno->fecha = $fecha = $fecha->format('Y-m-d');
        $_SESSION["Turno"] = serialize($turno);
    } else {
        $fecha = $turno->fecha;
    }
    ?>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="js/jquery-ui.js"></script>
   <script>
       <?php
        $listaDias = $link->query("select distinct dia from dias where medico_id=" . $turno->medico->id);
        $listaBloqueo = $link->query("select distinct dia from bloqueos where medico_id=" . $turno->medico->id);
        $disponibilidad = $link->query("select date_format(turnos.fecha,'%Y-%m-%d') as day,sum(duracion/30) as cantidad,
                                        max(tabla.turnos) as turnos,IFNULL(max(bloqueo.bloqueos),0) as bloqueos from
                                        (SELECT dia, SUM(TIME_TO_SEC(TIMEDIFF(horahasta,horadesde))/1800) as turnos
                                        from dias
                                        where medico_id =" . $turno->medico->id ."
                                        group by dia) as tabla
                                        join turnos on tabla.dia = DATE_FORMAT(turnos.fecha,'%w')
                                        left join
                                        (SELECT dia, SUM(TIME_TO_SEC(TIMEDIFF(horahasta,horadesde))/1800) as bloqueos
                                        from bloqueosparciales
                                        where medico_id =" . $turno->medico->id . "
                                        group by dia) as bloqueo
                                        on bloqueo.dia = DATE_FORMAT(turnos.fecha,'%Y-%m-%d')
                                        where turnos.medico_id =" . $turno->medico->id . "
                                        group by day
                                        HAVING cantidad+bloqueos >= turnos");
        ?>
       var array = [8
           <?php foreach ($listaDias as $day) {
                echo "," . $day["dia"];
            }
            ?>
       ];
       var full = ['2221-03-15'
           <?php foreach ($disponibilidad as $day) {
                echo ",'" . $day["day"] . "'";
            }
            ?>
       ];
       var bloqueo = ['2221-03-15'
           <?php foreach ($listaBloqueo as $day) {
                echo ",'" . $day["dia"] . "'";
            }
            ?>
       ];
       $("#datepicker").datepicker({
           dateFormat: "yy-mm-dd",

           beforeShowDay: function(date) {
               var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
               var day = date.getDay();
               if (full.indexOf(string) != -1) {
                   return [true, "Full", ""];
               }
               if (bloqueo.indexOf(string) != -1) {
                   return [false, "", ""];
               }
               if (array.indexOf(day) != -1) {
                   return [true, "Highlighted", ""];
               } else {
                   return [false, "", ""];
               }
           }
       });
       $("#datepicker").datepicker("setDate", "<?= date_format(date_create($fecha), "Y-m-d") ?>");
   </script>
   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <a onclick="paginaPrincipal('turnosduracion.php')" class="btn btn-success pull-right">Atrás</a>
               <h3 class="menu-text">Turnos del médico <?= $turno->medico->apellido . ", " . $turno->medico->nombre; ?></h3>
               <h4 class="menu-text">Duración del tratamiento <?= $turno->duracion . " minutos"; ?></h4>
               <!-- /.panel-heading -->

               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <div class="col-lg-6">
                               <form role="form" action="" name="envio" id="envio">
                                   <div class="form-group">
                                       <label>Seleccione la fecha del turno</label>
                                       <div id="datepicker" onchange="cambio()"></div>
                                   </div>
                               </form>
                               <p>Turnos para la fecha : <?= date_format(date_create($fecha), "d/m/Y") ?></p>
                           </div>

                           <div class="col-lg-6">
                               <div class="col-lg-12">
                                   <?php
                                    // $diaSemana = date_format(date_create($fecha), "w");
                                    $diaFecha = date_format(date_create($fecha), "Y-m-d");
                                    $dia = date_format(date_create($fecha), "w");
                                    $horarios = $link->query("select horadesde,horahasta 
                                    from dias where medico_id=" . $turno->medico->id . " and
                                    dia=" . $dia . " order by CONVERT(horadesde,TIME)");

                                    foreach ($horarios as $item) {
                                        $hora = new DateTime($fecha . ' ' . $item["horadesde"]);
                                        $hora2 = new DateTime($fecha . ' ' . $item["horahasta"]);

                                        while ($hora->format("H:i") < $hora2->format("H:i")) {
                                            $resultbloq = $link->query("select * from bloqueosparciales where dia='".$diaFecha."'
                                            and TIME('". $hora->format("H:i")."') >= TIME(horadesde) AND
                                            TIME('" . $hora->format("H:i") . "') < TIME(horahasta) AND 
                                            medico_id=".$turno->medico->id);
                            
                                            if (mysqli_num_rows($resultbloq) == 0) {
                                                $result = $link->query("select fecha,turnos.id as id, pacientes.nombre as nombre,duracion,
                                                    pacientes.apellido as apellido, tratamientos.codigo as codigo, tratamientos.denominacion
                                                    from turnos
                                                    join pacientes on pacientes.id = turnos.paciente_id
                                                    join tratamientos on tratamientos.id = turnos.tratamiento_id
                                                    where medico_id =" . $turno->medico->id . "
                                                    and fecha='" . $hora->format("Y-m-d H:i:00") . "'");
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_array($result);
                                                    $hasta = new DateTime($row["fecha"]);
                                                    $hasta = $hasta->add(new DateInterval("PT".$row["duracion"]."M"));
                                                    echo "<div class='horarioocupado' onclick=eliminarTurno(" . $row['id'] . ")>
                                                        ".$row["apellido"] . ', ' . $row["nombre"] . " - "
                                                        .$row["denominacion"] . ' (' . $row["codigo"] . ")<br>
                                                        de ". $hora->format("H:i")." a ". $hasta->format("H:i")
                                                        . "</div>";
                                                    $hora->add(new DateInterval("PT".$row["duracion"]."M"));
                                                } else {
                                                    echo "<div class='horario' onclick=registroHora('" . $hora->format('H:i') . "')>"
                                                        . $hora->format("H:i")
                                                        . "</div>";
                                                    $hora->add(new DateInterval("PT30M"));
                                                }
                                            }
                                            else
                                            { 
                                                $rowb = mysqli_fetch_array($resultbloq);
                                                echo "
                                                <div class='horarioocupado'>
                                                
                                                        <p>".$hora->format("H:i")." 
                                                        Bloqueado por: ".$rowb['motivo']."</p>
                                                        </div>";
                                                $hora->add(new DateInterval("PT30M"));
                                            }

                                            
                                        }
                                    }


                                    ?>
                               </div>
                           </div>
                       </div>
                       <div id="sub-pagina">

                       </div>
                   </div>
                   <!-- /.row -->

               </div>

           </div>
           <!-- /.panel -->

       </div>
   </div>
   <!-- /.col-lg-8 -->

   <script>
       cambio = () => {
           var fec = $("#datepicker").datepicker({
               dateFormat: 'dd,MM,yyyy'
           }).val()
           setFecha(fec);
       }

       function registroHora(fec) {
           setHora(fec);
       }
   </script>