<?php
// Include config file
require_once 'config.php';
?>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                REGISTRO DE NUEVO CLIENTE
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form name="envio" id="envio" role="form" action="" method="post">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input class="form-control" required id="nombre" name="nombre" maxlength="20" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" required class="form-control" id="apellido" name="apellido" maxlength="20" placeholder="Apellido">
                            </div>
                            <div class="form-group">
                                <label>Dirección o Barrio Privado</label>
                                <input type="text" required class="form-control" id="direccion" name="direccion" maxlength="150" placeholder="Dirección o Barrio Privado">
                            </div>
                            <div class="form-group">
                                <label>Zona de Entrega</label>

                                <select id="zona" name="zona" class="form-control" required>
                                    <?php
                                    $sql1 = "SELECT *
                                    FROM zona
                                    where Baja='False'
                                    order by Nombre";
                                    if ($result1 = mysqli_query($link, $sql1)) {
                                        if (mysqli_num_rows($result1) > 0) {
                                            while ($row = mysqli_fetch_array($result1)) {
                                                echo "<option value='" . $row["Id"] . "'>" . $row["Nombre"] . " - " . $row["Ubicacion"] . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label>DNI (Necesario para ingresar al sistema)</label>
                                <input type="text" required class="form-control" id="dni" name="dni" minlength="6" maxlength="15" placeholder="DNI Necesario para ingresar al sistema">
                            </div> -->
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" required class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Telefono">
                            </div>
                            <div class="form-group">
                                <label>E-Mail (Necesario para ingresar al sistema)</label>
                                <input type="email" required class="form-control" name="email" id="email" maxlength="60" placeholder="E-Mail">
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input required class="form-control" id="pass" name="pass" minlength="8" maxlength="30" placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <label>Repetir contraseña</label>
                                <input required class="form-control" id="passrepeat" name="passrepeat" minlength="8" maxlength="20" placeholder="Repetir Contraseña">
                            </div>

                            <button type="submit" id="Send" name="Send" class="btn btn-default">Confirmar Registro</button>
                        </form>
                    </div>

                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<script>
    $("#envio").on("submit", function(e) {
        $('#Send').attr("disabled", true);
        e.preventDefault();
        var telefono = document.envio.telefono.value;
        var email = document.envio.email.value;
        // var dni = document.envio.dni.value;
        var nombre = document.envio.nombre.value;
        var apellido = document.envio.apellido.value;
        var direccion = document.envio.direccion.value;
        var pass = document.envio.pass.value;
        var passrepeat = document.envio.passrepeat.value;
        var zona = document.envio.zona.value;

        var parametros = {
            "nombre": nombre,
            "telefono": telefono,
            "email": email,
            // "dni": dni,
            "apellido": apellido,
            "direccion": direccion,
            "zona": zona,
            "pass": pass,
            "passrepeat": passrepeat
        };

        $.ajax({
            url: 'Accion/registrocliente.php',
            type: 'POST',
            data: parametros,
            cache: false,
            success: function(response) {
                if (response != '') {
                    swal(response, {
                        buttons: false,
                        icon: "error",
                        timer: 2000,
                    });
                    $('#Send').attr("disabled", false);
                } else {
                    logear(email, pass);
                }
            }
        });
    });
</script>