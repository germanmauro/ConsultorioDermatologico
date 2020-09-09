<?php
// Include config file
require_once '../config.php';



//Definición de variables
$nombre = $apellido = $pass = $passrepeat = $direccion = $zona = $dni = $email = $telefono = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $direccion = $_POST["direccion"];
    $zona = $_POST["zona"];
    // $dni = $_POST["dni"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $pass = $_POST["pass"];
    $passrepeat = $_POST["passrepeat"];

    if ($pass == $passrepeat) {
        $result = $link->query("select * from usuario where Email='" . $email . "'");
        if (mysqli_num_rows($result) == 0) {
            // Prepare an insert statement
            $sql = "INSERT INTO  usuario(Nombre,Apellido,User,Direccion,Telefono,Email,Zona,Pass,Perfil) VALUES(?,?,?,?,?,?,?,?,'Cliente')";

            if ($stmt = mysqli_prepare($link, $sql) or die(mysqli_error($link))) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssssss", $p1, $p2, $p3, $p4, $p5,$p6, $p7, $p8);

                // Set parameters
                $p1 = $nombre;
                $p2 = $apellido;
                $p3 = $email;
                $p4 = $direccion;
                $p5 = $telefono;
                $p6 = $email;
                $p7 = $zona;
                $p8 = password_hash($pass, PASSWORD_DEFAULT);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    $asunto = "¡Registro en Verdulería Super Verde!";
                    $header = "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html; charset=UTF-8\r\n";
                    $header .= "from: superverdefyv@gmail.com \r\n";
                    $mensajeCorreo = "Su registro ha sido exitoso<br>" .  "DATOS DE INGRESO:<BR> 
                    Usuario: " . $email . "<br>" . "Contraseña: " . $pass. "<br><br> Gracias por elegirnos <br><br>
                    Verdulería Super Verde";
                    mail($email, $asunto, $mensajeCorreo, $header);
                    // Records created successfully. Redirect to landing page
                    echo "";
                } else {
                    echo "Ocurrio un error.";
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);


            // Close connection
            mysqli_close($link);
        } else {
            echo "Ya hay un usuario registrado con ese E-Mail";
        }
    } else {
        echo "La repetición de la contraseña no coincide";
    }
}
?>