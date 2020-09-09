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
  $pass = trim($_POST["pass"]);

  // Prepare a select statement
  $sql = "SELECT User, Pass,Perfil,Nombre,Apellido,UltimoIngreso FROM usuario WHERE Baja='False' AND User ='" . $username . "'";

  if ($result = mysqli_query($link, $sql) or die($link)) {
    if (mysqli_num_rows($result) > 0) {
      /*save the username to the session */
      ini_set('session.cache_expire', 84600);
      ini_set('session.cache_limiter', 'none');
      ini_set('session.cookie_lifetime', 84600);
      ini_set('session.gc_maxlifetime ', 84600);
      ini_set('session.gc_probability', 0);
      session_start();

      $_SESSION['Usuario'] = $username;
      while ($row = mysqli_fetch_array($result)) {
        $_SESSION['Perfil'] = $row['Perfil'];
        $_SESSION['Nombre'] = $row['Nombre'];
        $_SESSION['Apellido'] = $row['Apellido'];
        $_SESSION['UltimoIngreso'] = $row['UltimoIngreso'];
        $_SESSION['Alarma'] = 0;
        $hash = $row["Pass"];
      }
      if (password_verify($pass, $hash)) {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date('Y-m-d H:i:s');
        $link->query("Update usuario set UltimoIngreso ='" . $fecha . "' where Baja='False' AND Mail='" . $username . "'");

        header("location: index.php");
      } else {
        $password_err = 'La contraseña es inválida';
      }
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
  <title>Ingreso al Sistema</title>

  <link rel="stylesheet" href="css/style.css?v=5">


</head>

<body>

  <div class="login-page">
    <div class="form">

      <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="user" required type="text" maxlength=100 placeholder="Usuario (E-Mail)" />
        <input name="pass" required type="password" maxlength=15 placeholder="Contraseña" />
        <input name="formulario" value="ingreso" type="hidden" />
        <?php echo "<span class='mensaje'>" . $password_err . "</span>"; ?>
        <button>ingresar</button>

      </form>
      <a href="resetpass.php">Olvide mi contraseña</a>
    </div>

  </div>
  <!-- <script src='js/jquery.min.js'></script>

  <script src="js/index.js"></script> -->

</body>

</html>