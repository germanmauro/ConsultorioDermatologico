<?php
// Initialize the session
session_start();
require_once '../config.php';
require_once '../Clases/paciente.php';

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['Usuario']) || empty($_SESSION['Usuario'])) {
    header("location: ../login.php");
    exit;
}
if (
    !isset($_SESSION['Perfil']) || empty($_SESSION['Perfil'])
) {
    header("location: index.php");
    exit;
}

$paciente = new Paciente();
if (isset($_GET["id"])) {
    $paciente->cargar($_GET["id"]);
    $_SESSION["Paciente"] = serialize($paciente);
} elseif (isset($_SESSION["Turno"])) {
    $paciente = unserialize($_SESSION["Paciente"]);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historia Clínica</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloprincipal.css">
    <link rel="stylesheet" href="../css/accordion.css">
    <script src="../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../Tables/jquery.dataTables.css">
    <script src="../Tables/jquery.dataTables.js"></script>
    <script src=" ../js/bootstrap.min.js"> </script>
    <link href="../css/font-awesome/css/all.css" rel="stylesheet" type="text/css">
    <!-- alertas -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tabla').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                "order": []
            });
            $('#tablaarchivo').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                "order": []
            });
        });
    </script>

</head>


<body>
    <div class="wrapper">
        <div class="container-fluid" id="hc">
            <div class="row">
                <a href="index.php" class="btn btn-success">Volver</a>
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Historia Clínica</h2>
                    </div>
                    <div class="accordion">
                        <div class="accordion__item">
                            <div class="accordion__item__header">
                                <h3 class="titulo">DATOS DEL PACIENTE</h3>
                            </div>
                            <div class="accordion__item__content  collapse in">
                                <div class="col-md-12">
                                    <div class="item-hc">

                                        <h4><span class="nombre">Nombre:</span> <?= $paciente->apellido . ", " . $paciente->nombre ?></h4>
                                        <h4><span class="nombre">DNI:</span> <?= $paciente->dni ?></h4>
                                        <h4><span class="nombre">Dirección:</span> <?= $paciente->direccion . "<span class='nombre'> Localidad:</span> " . $paciente->localidad ?></h4>
                                        <h4><span class="nombre">Fecha de nacimiento</span> <?php
                                                                                            $fecha = new DateTime($paciente->fechanacimiento);
                                                                                            $hoy = new DateTime();
                                                                                            echo $fecha->format("d/m/Y"); ?> <span class="nombre">Edad:</span> <?= date_diff($fecha, $hoy)->y ?></h4>
                                        <h4><span class="nombre">Tel/Cel:</span> <?= $paciente->telefono ?> <span class="nombre">E-mail:</span> <?= $paciente->email ?></h4>
                                        <h4><span class="nombre">Profesión:</span> <?= $paciente->profesion ?></h4>
                                        <h4><span class="nombre">Alta:</span> <?= date_format(date_create($paciente->alta), "d/m/Y") ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion__item">
                            <div class="accordion__item__header">
                                <h3 class="titulo">ANTECEDENTES PERSONALES</h3>
                            </div>
                            <div class="accordion__item__content">
                                <div class="col-md-12">
                                    <div class="item-hc">

                                        <form name="envio" id="envio" role="form" action="" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Enfermedades Sistemáticas</h3>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Neurológico y/o psiquiátricos</label>
                                                            <textarea maxlength="2000" class="form-control" name="neurologico" cols="30" rows="1"><?= $paciente->historia->neurologico ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Cardiovascular</label>
                                                            <textarea maxlength="2000" class="form-control" name="cardiovascular" cols="30" rows="1"><?= $paciente->historia->cardiovascular ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Endocrionológico</label>
                                                            <textarea maxlength="2000" class="form-control" name="endocrinologico" cols="30" rows="1"><?= $paciente->historia->endocrinologico ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Pulmonar</label>
                                                            <textarea maxlength="2000" class="form-control" name="pulmonar" cols="30" rows="1"><?= $paciente->historia->pulmonar ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Digestivo</label>
                                                            <textarea maxlength="2000" class="form-control" name="digestivo" cols="30" rows="1"><?= $paciente->historia->digestivo ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Renal</label>
                                                            <textarea maxlength="2000" class="form-control" name="renal" cols="30" rows="1"><?= $paciente->historia->renal ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Dermatológico</label>
                                                            <textarea class="form-control" name="dermatologico" cols="30" rows="1"><?= $paciente->historia->dermatologico ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Hematológicas</label>
                                                            <textarea maxlength="2000" class="form-control" name="hematologicas" cols="30" rows="1"><?= $paciente->historia->hematologicas ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Otras</label>
                                                            <textarea maxlength="2000" class="form-control" name="antecedentesotros" cols="30" rows="1"><?= $paciente->historia->antecedentesotros ?></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Médicamentos</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Antigangrenantes</label>
                                                            <textarea maxlength="300" class="form-control" name="antigangrenantes" cols="30" rows="1"><?= $paciente->historia->antigangrenantes ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Anticoagulantes</label>
                                                            <textarea maxlength="300" class="form-control" name="anticoagulantes" cols="30" rows="1"><?= $paciente->historia->anticoagulantes ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Analgésicos</label>
                                                            <textarea maxlength="300" class="form-control" name="analgesicos" cols="30" rows="1"><?= $paciente->historia->analgesicos ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Suplementos vitamínicos</label>
                                                            <textarea maxlength="300" class="form-control" name="suplementosvitaminicos" cols="30" rows="1"><?= $paciente->historia->suplementosvitaminicos ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Antidepresivos</label>
                                                            <textarea class="form-control" name="antidepresivos" cols="30" rows="1"><?= $paciente->historia->antidepresivos ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Otros</label>
                                                            <textarea maxlength="2000" class="form-control" name="medicamentosotros" cols="30" rows="1"><?= $paciente->historia->medicamentosotros ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Alergia e hipersensibilidad</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fármaco</label>
                                                            <textarea maxlength="300" class="form-control" name="alergiafarmaco" cols="30" rows="1"><?= $paciente->historia->alergiafarmaco ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Anestésico Local</label>
                                                            <textarea maxlength="300" class="form-control" name="alergiaanestesicolocal" cols="30" rows="1"><?= $paciente->historia->alergiaanestesicolocal ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Hábitos</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Tabaco</label>
                                                            <input type="text" maxlength="100" class="form-control" name="tabaco" value="<?= $paciente->historia->tabaco ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alcohol</label>
                                                            <input type="text" maxlength="100" class="form-control" name="alcohol" value="<?= $paciente->historia->alcohol ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Actividad física</label>
                                                            <input type="text" maxlength="100" class="form-control" name="actividadfisica" value="<?= $paciente->historia->actividadfisica ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Exposición solar</label>
                                                            <input type="text" maxlength="100" class="form-control" name="exposicionsolar" value="<?= $paciente->historia->exposicionsolar ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Tratamiento estéticos previos</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Toxina botulínica</label>
                                                            <input type="text" maxlength="100" class="form-control" name="toxinabotulinica" value="<?= $paciente->historia->toxinabotulinica ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Rellenos de ácido hialuronico</label>
                                                            <input type="text" maxlength="100" class="form-control" name="acidohialuronico" value="<?= $paciente->historia->acidohialuronico ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Antecedentes quirúrgicos</label>
                                                            <input type="text" maxlength="500" class="form-control" name="antecedentesquirurgicos" value="<?= $paciente->historia->antecedentesquirurgicos ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h4 class="subtitulo">Otros</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Antecedentes traumáticos y/o quirúrgicos</label>
                                                            <textarea maxlength="1000" class="form-control" name="antecedentestraumaticos" cols="30" rows="1"><?= $paciente->historia->antecedentestraumaticos ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Cicatrización</label>
                                                            <textarea maxlength="2000" class="form-control" name="cicatrizacion" cols="30" rows="1"><?= $paciente->historia->cicatrizacion ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Reacciones vagales</label>
                                                            <textarea maxlength="1000" class="form-control" name="reaccionesvagales" cols="30" rows="1"><?= $paciente->historia->reaccionesvagales ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Dismorfofobia</label>
                                                            <textarea maxlength="1000" class="form-control" name="dismorfofobia" cols="30" rows="1"><?= $paciente->historia->dismorfofobia ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Plan de vacunacion antitetánica</label>
                                                            <textarea maxlength="1000" class="form-control" name="vacunacionantitetanica" cols="30" rows="1"><?= $paciente->historia->vacunacionantitetanica ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fragilidad capilar</label>
                                                            <textarea maxlength="1000" class="form-control" name="fragilidadcapilar" cols="30" rows="1"><?= $paciente->historia->fragilidadcapilar ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Tratamiento odontológica reciente</label>
                                                            <textarea maxlength="2000" class="form-control" name="tratamientoodontologico" cols="30" rows="1"><?= $paciente->historia->tratamientoodontologico ?></textarea>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if (in_array($_SESSION['Perfil'], ["medico", "admin"])) {
                                                        echo "
                                                        <div class='col-md-12'>
                                                            <button type='submit' id='Send' name='Send' class='btn btn-success'>Actualizar Datos</button>
                                                        </div>";
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__item">
                            <div class="accordion__item__header">
                                <h3 class="titulo">CONSULTAS</h3>
                            </div>
                            <div class="accordion__item__content collapse in">
                                <div class="col-md-12">
                                    <!-- <div class="item-hc"> -->
                                    <div class='col-lg-6'>
                                        <table id='tabla' class='display tablagrande'>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Fecha</th>
                                                    <th>Motivo</th>
                                                    <th>Detalle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $result = $link->query("SELECT id,DATE_FORMAT(fecha,'%d/%m/%Y') as fecha,
                                                    motivo,detalle
                                                    FROM consultas
                                                    WHERE paciente_id =".$paciente->id."
                                                    order by consultas.fecha desc,id desc");

                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<tr>";
                                                    echo "<td>";
                                                    echo "<button id='" . $row["id"] . "'  onclick='eliminarConsulta(" . $row['id'] . ")' class='btnmenu'><i class='fas fa-trash'></i></button>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["fecha"];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["motivo"];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["detalle"];
                                                    echo "</td>";

                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>
                                    <?php
                                    if (in_array($_SESSION['Perfil'], ["medico", "admin"])) {
                                        echo "
                                    <div class='col-lg-6'>
                                        <form name='consulta' id='consulta' role='form' action='' autocomplete='off'>

                                            <h4 class='subtitulo'>Nueva consulta</h3>";


                                        $hoy = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
                                        $hoy = $hoy->format("Y-m-d");
                                        echo "
                                                <div class='form-group'>
                                                    <label>Fecha</label>
                                                    <input type='date' class='form-control' name='fecha' value='" . $hoy . "'>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Motivo</label>
                                                    <textarea maxlength='2000' class='form-control' name='motivo' cols='30' rows='2'></textarea>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Detalle</label>
                                                    <textarea maxlength='2000' class='form-control' name='detalle' cols='30' rows='3'></textarea>
                                                </div>
                                                <button type='submit' id='Send1' name='Send' class='btn btn-success'>Guardar consulta</button>
                                        </form>

                                        <!-- </div> -->
                                    </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__item">
                            <div class="accordion__item__header">
                                <h3 class="titulo">ARCHIVOS</h3>
                            </div>
                            <div class="accordion__item__content">
                                <div class="col-md-12">
                                    <!-- <div class="item-hc"> -->
                                    <div class='col-lg-6'>
                                        <table id='tablaarchivo' class='display tablagrande'>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Fecha</th>
                                                    <th>Descripcion</th>
                                                    <th>Archivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $result = $link->query("SELECT id,DATE_FORMAT(fecha,'%d/%m/%Y') as fecha,
                                                    descripcion,archivo
                                                    FROM archivos
                                                    WHERE paciente_id = ".$paciente->id."
                                                    order by archivos.fecha desc,id desc");


                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<tr>";
                                                    echo "<td>";
                                                    echo "<button id='" . $row["id"] . "'  onclick='eliminarConsulta(" . $row['id'] . ")' class='btnmenu'><i class='fas fa-trash'></i></button>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["fecha"];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["motivo"];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $row["detalle"];
                                                    echo "</td>";

                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>
                                    <?php
                                    if (in_array($_SESSION['Perfil'], ["medico", "admin"])) {
                                        echo "
                                    <div class='col-lg-6'>
                                        <form name='formarchivo' id='formarchivo' role='form' action='' autocomplete='off' enctype='multipart/form-data'>

                                            <h4 class='subtitulo'>Nueva consulta</h3>";


                                        $hoy = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
                                        $hoy = $hoy->format("Y-m-d");
                                        echo "
                                                <div class='form-group'>
                                                    <label>Fecha</label>
                                                    <input type='date' class='form-control' name='fecha' value='" . $hoy . "'>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Descripción</label>
                                                    <input type='text' class='form-control' name='descripcion' maxlength=100>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Archivo</label>
                                                    <input type='file' name='archivo' id='archivo' accept='.pdf,image/*' class='form-control'>
                                                </div>
                                                <button type='submit' id='Send2' name='Send' class='btn btn-success'>Guardar consulta</button>
                                        </form>

                                        <!-- </div> -->
                                    </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
</body>
<script>
    $("#envio").on("submit", function(e) {
        $('#Send').attr("disabled", true);
        e.preventDefault();
        var neurologico = document.envio.neurologico.value;
        var cardiovascular = document.envio.cardiovascular.value;
        var endocrinologico = document.envio.endocrinologico.value;
        var pulmonar = document.envio.pulmonar.value;
        var digestivo = document.envio.digestivo.value;
        var renal = document.envio.renal.value;
        var dermatologico = document.envio.dermatologico.value;
        var hematologicas = document.envio.hematologicas.value;
        var antecedentesotros = document.envio.antecedentesotros.value;

        var antigangrenantes = document.envio.antigangrenantes.value;
        var anticoagulantes = document.envio.anticoagulantes.value;
        var analgesicos = document.envio.analgesicos.value;
        var suplementosvitaminicos = document.envio.suplementosvitaminicos.value;
        var antidepresivos = document.envio.antidepresivos.value;
        var medicamentosotros = document.envio.medicamentosotros.value;

        var alergiafarmaco = document.envio.alergiafarmaco.value;
        var alergiaanestesicolocal = document.envio.alergiaanestesicolocal.value;

        var tabaco = document.envio.tabaco.value;
        var alcohol = document.envio.alcohol.value;
        var actividadfisica = document.envio.actividadfisica.value;
        var exposicionsolar = document.envio.exposicionsolar.value;

        var toxinabotulinica = document.envio.toxinabotulinica.value;
        var acidohialuronico = document.envio.acidohialuronico.value;
        var antecedentesquirurgicos = document.envio.antecedentesquirurgicos.value;

        var antecedentestraumaticos = document.envio.antecedentestraumaticos.value;
        var cicatrizacion = document.envio.cicatrizacion.value;
        var reaccionesvagales = document.envio.reaccionesvagales.value;
        var dismorfofobia = document.envio.dismorfofobia.value;
        var vacunacionantitetanica = document.envio.vacunacionantitetanica.value;
        var fragilidadcapilar = document.envio.fragilidadcapilar.value;
        var tratamientoodontologico = document.envio.tratamientoodontologico.value;

        var parametros = {
            "neurologico": neurologico,
            "cardiovascular": cardiovascular,
            "endocrinologico": endocrinologico,
            "pulmonar": pulmonar,
            "digestivo": digestivo,
            "renal": renal,
            "dermatologico": dermatologico,
            "hematologicas": hematologicas,
            "antecedentesotros": antecedentesotros,

            "antigangrenantes": antigangrenantes,
            "anticoagulantes": anticoagulantes,
            "analgesicos": analgesicos,
            "suplementosvitaminicos": suplementosvitaminicos,
            "antidepresivos": antidepresivos,
            "medicamentosotros": medicamentosotros,

            "alergiafarmaco": alergiafarmaco,
            "alergiaanestesicolocal": alergiaanestesicolocal,

            "tabaco": tabaco,
            "alcohol": alcohol,
            "actividadfisica": actividadfisica,
            "exposicionsolar": exposicionsolar,

            "toxinabotulinica": toxinabotulinica,
            "acidohialuronico": acidohialuronico,
            "antecedentesquirurgicos": antecedentesquirurgicos,

            "antecedentestraumaticos": antecedentestraumaticos,
            "cicatrizacion": cicatrizacion,
            "reaccionesvagales": reaccionesvagales,
            "dismorfofobia": dismorfofobia,
            "vacunacionantitetanica": vacunacionantitetanica,
            "fragilidadcapilar": fragilidadcapilar,
            "tratamientoodontologico": tratamientoodontologico
        };
        swal("¿Desea actualizar la información del paciente?", {
                icon: "warning",
                buttons: {


                    catch: {
                        text: "SÍ",
                        value: "catch",
                    },
                    cancel: "NO",

                },
            })
            .then((value) => {
                switch (value) {

                    case "catch":
                        $.ajax({
                            url: '../Accion/actualizarHistoria.php',
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
                                    $('#Send').attr("disabled", false);
                                } else {
                                    swal("Datos actualizados correctamente", {
                                        buttons: false,
                                        icon: "success",
                                        timer: 4000,
                                    });

                                }
                            }
                        });
                        break;

                }
            });
    });

    //Nueva consulta
    $("#consulta").on("submit", function(e) {
        $('#Send1').attr("disabled", true);
        e.preventDefault();

        var fecha = document.consulta.fecha.value;
        var motivo = document.consulta.motivo.value;
        var detalle = document.consulta.detalle.value;

        var parametros = {
            "fecha": fecha,
            "motivo": motivo,
            "detalle": detalle
        };
        swal("¿Desea registrar la consulta?", {
                icon: "warning",
                buttons: {


                    catch: {
                        text: "SÍ",
                        value: "catch",
                    },
                    cancel: "NO",

                },
            })
            .then((value) => {
                switch (value) {

                    case "catch":
                        $.ajax({
                            url: '../Accion/registrarConsulta.php',
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
                                    $('#Send1').attr("disabled", false);
                                } else {
                                    // paginaPrincipal('turnoconfirma.php');
                                    location.reload();
                                    // swal("Consulta registrada correctamente correctamente", {
                                    //     buttons: false,
                                    //     icon: "success",
                                    //     timer: 4000,
                                    // });

                                }
                            }
                        });
                        break;

                }
            });
    });
    //Eliminar consulta
    eliminarConsulta = (id) => {
        var parametros = {
            "id": id
        };
        swal("¿Desea eliminar la consulta?", {
                icon: "warning",
                buttons: {


                    catch: {
                        text: "SÍ",
                        value: "catch",
                    },
                    cancel: "NO",

                },
            })
            .then((value) => {
                switch (value) {

                    case "catch":
                        $.ajax({
                            url: '../Accion/eliminarConsulta.php',
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
                                    $('#Send1').attr("disabled", false);
                                } else {
                                    // paginaPrincipal('turnoconfirma.php');
                                    location.reload();
                                }
                            }
                        });
                        break;

                }
            });
    }
    //Nuevo archivo
    $("#formarchivo").on("submit", function(e) {
        // $('#Send2').attr("disabled", true);
        e.preventDefault();
        alert(archivo);
        var file_data = $('#archivo').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        var fecha = document.formarchivo.fecha.value;
        var descripcion = document.formarchivo.descripcion.value;
        var parametros = {
            "fecha": fecha,
            "descripcion": descripcion,
            form_data
        };
        swal("¿Desea registrar el archivo?", {
                icon: "warning",
                buttons: {
                    catch: {
                        text: "SÍ",
                        value: "catch",
                    },
                    cancel: "NO",

                },
            })
            .then((value) => {
                switch (value) {

                    case "catch":
                        $.ajax({
                            url: '../Accion/registrarArchivo.php',
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
                                    $('#Send1').attr("disabled", false);
                                } else {
                                    // paginaPrincipal('turnoconfirma.php');
                                    location.reload();
                                    // swal("Consulta registrada correctamente correctamente", {
                                    //     buttons: false,
                                    //     icon: "success",
                                    //     timer: 4000,
                                    // });

                                }
                            }
                        });
                        break;

                }
            });
    });
    //Eliminar consulta
    eliminarArchivo = (id) => {
        var parametros = {
            "id": id
        };
        swal("¿Desea eliminar el archivo?", {
                icon: "warning",
                buttons: {


                    catch: {
                        text: "SÍ",
                        value: "catch",
                    },
                    cancel: "NO",

                },
            })
            .then((value) => {
                switch (value) {

                    case "catch":
                        $.ajax({
                            url: '../Accion/eliminarArchivo.php',
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
                                } else {
                                    location.reload();
                                }
                            }
                        });
                        break;

                }
            });
    }
</script>
<script src="../js/accordion.js"></script>

</html>