   <?php
    // session_start();
    require_once 'config.php';
    ?>
   <style type="text/css">
       .wrapper {

           margin: 0 auto;
       }

       .page-header h2 {
           margin-top: 0;
       }

       table tr td:last-child a {
           margin-right: 15px;
       }
   </style>
   <script type="text/javascript">
       $(document).ready(function() {
           $('[data-toggle="tooltip"]').tooltip();
       });
   </script>
   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <h3 class="menu-text">Selección de Médico</h3>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <table id='tablamedicos' class='display menutable'>
                               <thead>
                                   <tr>
                                       <th>Apellido</th>
                                       <th>Nombre</th>
                                       <th>Especialidad</th>
                                       <th></th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <?php
                                    $result = $link->query("select * from usuarios 
                                    where baja='False' and (perfil='medico' or perfil = 'submedico')
                                    order by apellido");

                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo $row["apellido"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["nombre"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["especialidad"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<button id='" . $row["id"] . "' title='Ver turnos'  onclick='cargaMedico(" . $row['id'] . ")' class='btnmenu'><i class='fas fa-clock'></i></button>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                               </tbody>
                           </table>

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
   <!-- /.col-lg-8 -->

   
   <script type="text/javascript">
       $(document).ready(function() {
           $('#tablamedicos').DataTable({
               "pageLength": 5,
               "lengthMenu": [5, 10, 25, 50, 75, 100],
               columnDefs: [{
                   orderable: false,
                   targets: [3]
               }]

           });
       });
   </script>