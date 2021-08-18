   <?php
    session_start();
    require_once 'config.php';

    ?>

   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <h3 class="menu-text">Listado de ventas
                   <button onclick='cierreCaja()'><i class='fas fa-money-bill-alt' title='Ver cierre de caja'></i></button>
               </h3>

               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-12'>
                           <table id='tablapedido' class='display menutable'>
                               <thead>
                                   <tr>
                                       <th></th>
                                       <th></th>
                                       <th></th>
                                       <th>Fecha</th>
                                       <th>Factura</th>
                                       <th>Paciente</th>
                                       <th>Forma Pago</th>
                                       <th>Observaciones</th>
                                       <th>Total</th>


                                   </tr>
                               </thead>
                               <tbody>
                                   <?php
                                    $result = $link->query("SELECT ventas.id as id,CONCAT(pacientes.nombre, ' ',pacientes.apellido) AS paciente,
                                        DATE_FORMAT(ventas.fecha,'%d/%m/%Y') as fecha,
                                        total, formaspago.nombre as formapago,factura,observaciones,
                                        ventas.usuario_id as usuario
                                        FROM ventas
                                        join pacientes on pacientes.id = ventas.paciente_id
                                        join formaspago on formaspago.id = ventas.formapago_id
                                        order by ventas.fecha desc");


                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<button  onclick='cargaVentaDetalle(" . $row['id'] . ")' class='btnmenu'><i class='far fa-list-alt' title='Detalle'></i></button>";
                                        echo "</td>";
                                        echo "<td>";
                                        if (
                                            in_array($_SESSION['Perfil'], ['medico', 'admin']) ||
                                            $_SESSION["Id"] == $row["usuario"]
                                        ) {
                                            echo "<button  onclick='cargaVenta(" . $row['id'] . ")' class='btnmenu'><i class='fa fa-edit' title='modificar'></i></button>";
                                        }
                                        echo "</td>";
                                        echo "<td>";
                                        if (
                                            in_array($_SESSION['Perfil'], ['medico', 'admin']) ||
                                            $_SESSION["Id"] == $row["usuario"]
                                        ) {
                                            echo "<button  onclick='eliminarVenta(" . $row['id'] . ")' class='btnmenu'><i class='fa fa-trash' title='Eliminar'></i></button>";
                                        }
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["fecha"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["factura"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["paciente"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["formapago"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["observaciones"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["total"];
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                               </tbody>
                           </table>

                       </div>
                       <div class='col-lg-12'>
                           <div id="sub-pagina">

                           </div>
                       </div>
                   </div>

               </div>
               <!-- /.row -->

           </div>

       </div>
       <!-- /.panel -->

   </div>
   <!-- /.col-lg-8 -->

   <script>
       $(document).ready(function() {
           $.fn.dataTable.moment('DD/MM/YYYY');
           $('#tablapedido').DataTable({
               columnDefs: [{
                   targets: [0,1,2],
                   orderable: false,
                   sorting: false
               }],
               "pageLength": 5,
               "lengthMenu": [5, 10, 25, 50, 75, 100],

           });
       });
   </script>
   <script>
       function cierreCaja() {
           subPagina('cierrecaja.php');
       }
       $("#envio").on("submit", function(e) {
           $('#Send').attr("disabled", true);
           e.preventDefault();
           var codigo = document.envio.codigo.value;
           var nombre = document.envio.nombre.value;
           var apellido = document.envio.apellido.value;
           var dni = document.envio.dni.value;
           var obrasocial = document.envio.obrasocial.value;
           var carnet = document.envio.carnet.value;
           var email = document.envio.email.value;
           var telefono = document.envio.telefono.value;
           var direccion = document.envio.direccion.value;
           var localidad = document.envio.localidad.value;
           var fechanacimiento = document.envio.fechanacimiento.value;
           var profesion = document.envio.profesion.value;
           var referido = document.envio.referido.value;

           var parametros = {
               "codigo": codigo,
               "nombre": nombre,
               "apellido": apellido,
               "dni": dni,
               "obrasocial": obrasocial,
               "carnet": carnet,
               "email": email,
               "telefono": telefono,
               "direccion": direccion,
               "localidad": localidad,
               "fechanacimiento": fechanacimiento,
               "profesion": profesion,
               "referido": referido
           };

           $.ajax({
               url: 'Accion/pacienteVenta.php',
               type: 'POST',
               data: parametros,
               cache: false,
               success: function(r) {
                   if (r != "") {
                       swal(r, {
                           buttons: false,
                           icon: "error",
                           timer: 3000,
                       });
                       //return r;
                   } else {
                       paginaPrincipal('ventaproducto.php');
                   }
               }
           });

       });
   </script>