<?php
// Include config file
require_once 'config.php';

// Destruir todas las variables de sesión.
session_start();
session_destroy();
// Define variables and initialize with empty values
$username = $password = $password_err = "";
$usernamenew = $passwordnew = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = trim($_POST["user"]);
  // $pass = trim($_POST["pass"]);

  // Prepare a select statement
  $sql = "SELECT User, Pass,Perfil,Nombre,Apellido,UltimoIngreso FROM usuario WHERE Baja='False' AND User ='" . $username . "'";

  if ($result = mysqli_query($link, $sql) or die($link)) {
    if (mysqli_num_rows($result) > 0) {
      //Creo la pass
      function generateRandomString($length = 8)
      {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
      }

      $newpass = generateRandomString();
      $pass = password_hash($newpass, PASSWORD_DEFAULT);
      $link->query("Update usuario set Pass='" . $pass . "' where User='" . $username . "'");
      //Envio email
      $asunto = "¡Registro en Verdulería Super Verde!";
      // $mensaje = $_POST['mensaje'];
      $header = "MIME-Version: 1.0\r\n";
      $header .= "Content-type: text/html; charset=UTF-8\r\n";
      $header .= "from: superverdefyv@gmail.com \r\n";
      $mensajeCorreo = "Su contraseña fue reseteada<br>" .  "DATOS DE INGRESO:<BR> 
                    Usuario: " . $username . "<br>" . "Contraseña: " . $newpass . "<br><br> Ingrese al sistema 
                    para modificarla <br><br>
                    Verdulería Super Verde";
      mail($username, $asunto, $mensajeCorreo, $header);
      $password_err = "Le enviamos al nueva contraseña a su e-mail<br><a href='login.php'>Ingresar al sistema</a>";
    } else {
      // Display an error message if password is not valid
      $password_err = 'El usuario no existe';
    }
  } else {
    echo "Oops! Something went wrong. Please try again later.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Recuperar Clave</title>

  <link rel="stylesheet" href="css/style.css?v=4">


</head>

<body>

  <div class="login-page">
    <div class="form">

      <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="user" required type="text" maxlength=100 placeholder="Usuario (E-Mail)" />
        <input name="formulario" value="ingreso" type="hidden" />

        <button>Recuperar Clave</button>
        <?php echo "<span class='mensaje'>" . $password_err . "</span>"; ?>
      </form>
    </div>

  </div>
  <!-- <script src='js/jquery.min.js'></script>

  <script src="js/index.js"></script> -->

</body>

</html>