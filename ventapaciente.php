   <?php
    session_start();
    require_once 'config.php';
    require_once 'Clases/venta.php';
    $venta = new Venta();
    if (isset($_SESSION["Venta"])) {
        $venta = unserialize($_SESSION["Venta"]);
    }
    $codigo = $nombre = $apellido = $telefono = $email = $dni = $direccion =
        $obrasocial = $carnet = $localidad = $fechanacimiento = $profesion = $referido = "";
    if (isset($_POST["Id"])) {
        $result = $link->query("SELECT * from pacientes
        where pacientes.baja='False' and id=" . $_POST["Id"]);
        $row = mysqli_fetch_array($result);
        $venta->paciente->codigo = $row["codigo"];
        $venta->paciente->apellido = $row["apellido"];
        $venta->paciente->nombre = $row["nombre"];
        $venta->paciente->obrasocial = $row["obrasocial_id"];
        $venta->paciente->carnet = $row["carnet"];
        $venta->paciente->dni = $row["dni"];
        $venta->paciente->email = $row["email"];
        $venta->paciente->telefono = $row["telefono"];
        $venta->paciente->direccion = $row["direccion"];
        $venta->paciente->localidad = $row["localidad"];
        $venta->paciente->fechanacimiento = $row["fechanacimiento"];
        $venta->paciente->profesion = $row["profesion"];
        $venta->paciente->referido = $row["referido"];
    }

    ?>

   <div class='row'>
       <div class='col-lg-12'>

           <div class='panel panel-default'>
               <a onclick="paginaPrincipal('ventafecha.php')" class="btn btn-success pull-right">Atrás</a>
               <h3 class="menu-text">Selección de Paciente</h3>
               <h4 class="menu-text">Fecha <?= date_format(date_create($venta->fecha), 'd/m/Y') ?> - Factura N° <?= $venta->factura ?></h4>
               <!-- /.panel-heading -->
               <div class='panel-body'>
                   <div class='row'>

                       <div class='col-lg-6'>
                           <table id='tablapedido' class='display menutable'>
                               <thead>
                                   <tr>
                                       <th></th>
                                       <th>Código</th>
                                       <th>Apellido</th>
                                       <th>Nombre</th>
                                       <th>DNI</th>
                                       <th>Obra Social</th>
                                       <th>Carnet</th>
                                       <th>Teléfono</th>
                                       <th>Email</th>
                                       <th>Dirección</th>
                                       <th>Localidad</th>
                                       <th>Fecha de nacimiento</th>
                                       <th>Profesión</th>
                                       <th>Referido</th>

                                   </tr>
                               </thead>
                               <tbody>
                                   <?php
                                    $result = $link->query("SELECT pacientes.id as id,pacientes.codigo,pacientes.nombre,pacientes.apellido,pacientes.dni,
                                        obrassociales.nombre as obrasocial,pacientes.carnet, pacientes.telefono,
                                        pacientes.email,pacientes.direccion, pacientes.localidad, 
                                        DATE_FORMAT(pacientes.fechanacimiento,'%d/%m/%Y') as fechanacimiento,
                                        pacientes.profesion, pacientes.referido
                                        FROM pacientes
                                        join obrassociales on obrassociales.id = pacientes.obrasocial_id
                                        where pacientes.baja='False' 
                                        order by apellido");


                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo "<button id='" . $row["id"] . "'  onclick='cargapacienteventa(" . $row['id'] . ")' class='btnmenu'><i class='fas fa-plus'></i></button>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["codigo"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["apellido"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["nombre"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["dni"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["obrasocial"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["carnet"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["telefono"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["email"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["direccion"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["localidad"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["fechanacimiento"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["profesion"];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row["referido"];
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                               </tbody>
                           </table>

                       </div>
                       <div class='col-lg-6'>
                           <form name="envio" id="envio" role="form" action="" autocomplete="off">
                               <div class="form-group">
                                   <label>Código (Si queda vacío toma el número más alto)</label>
                                   <input type="number" name="codigo" min=0 max=10000 class="form-control" value="<?php echo $venta->paciente->codigo; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Apellido</label>
                                   <input type="text" name="apellido" required maxlength=20 class="form-control" value="<?php echo $venta->paciente->apellido; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Nombre</label>
                                   <input type="text" name="nombre" required maxlength=20 class="form-control" value="<?php echo $venta->paciente->nombre; ?>">
                               </div>
                               <div class="form-group">
                                   <label>DNI</label>
                                   <input type="text" name="dni" required maxlength=20 class="form-control" value="<?php echo $venta->paciente->dni; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Obra Social</label>
                                   <select required name="obrasocial" class="form-control">
                                       <?php
                                        $result = $link->query("select * from obrassociales where baja='False'");
                                        foreach ($result as $row) {
                                            echo "<option " .
                                                ($venta->paciente->obrasocial == $row["id"] ? 'selected' : '') . "
                                         value='" . $row['id'] . "'>" . $row["nombre"] . "</option>";
                                        }
                                        ?>
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>Número Carnet</label>
                                   <input type="text" name="carnet" required maxlength=30 class="form-control" value="<?php echo $venta->paciente->carnet; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Teléfono</label>
                                   <input type="text" name="telefono" required maxlength=20 class="form-control" value="<?php echo $venta->paciente->telefono; ?>">
                               </div>
                               <div class="form-group">
                                   <label>E-mail</label>
                                   <input type="email" name="email" maxlength=200 class="form-control" value="<?php echo $venta->paciente->email; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Dirección</label>
                                   <input type="text" name="direccion" required maxlength=200 class="form-control" value="<?php echo $venta->paciente->direccion; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Localidad</label>
                                   <input type="text" name="localidad" required maxlength=100 class="form-control" value="<?php echo $venta->paciente->localidad; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Fecha de Nacimiento</label>
                                   <input type="date" name="fechanacimiento" maxlength=200 class="form-control" value="<?php echo $venta->paciente->fechanacimiento; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Profesion</label>
                                   <input type="text" name="profesion" required maxlength=100 class="form-control" value="<?php echo $venta->paciente->profesion; ?>">
                               </div>
                               <div class="form-group">
                                   <label>Referido</label>
                                   <input type="text" name="referido" required maxlength=200 class="form-control" value="<?php echo $venta->paciente->referido; ?>">
                               </div>

                               <button type="submit" id="Send" name="Send" class="btn btn-success">Siguiente</button>
                           </form>

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

   <script>
       $(document).ready(function() {
           $('#tablapedido').DataTable({
               "pageLength": 5,
               "lengthMenu": [5, 10, 25, 50, 75, 100],
               columnDefs: [{
                   orderable: false,
                   targets: [0]
               }]

           });
       });
   </script>
   <script>
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