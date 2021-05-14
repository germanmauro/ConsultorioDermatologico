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
  $sql = "SELECT id, user, pass,perfil,nombre,apellido,ultimoingreso FROM usuarios WHERE baja='False' AND user ='" . $username . "'";

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
        $_SESSION['Id'] = $row['id'];
        $_SESSION['Perfil'] = $row['perfil'];
        $_SESSION['Nombre'] = $row['nombre'];
        $_SESSION['Apellido'] = $row['apellido'];
        $_SESSION['UltimoIngreso'] = $row['ultimoingreso'];
        $hash = $row["pass"];
      }
      if (password_verify($pass, $hash)) {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date('Y-m-d H:i:s');
        $link->query("Update usuarios set ultimoingreso ='" . $fecha . "' where baja='false'");

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
        <input name="user" required type="text" maxlength=100 placeholder="Usuario" />
        <input name="pass" required type="password" maxlength=15 placeholder="Contraseña" />
        <input name="formulario" value="ingreso" type="hidden" />
        <?php echo "<span class='mensaje'>" . $password_err . "</span>"; ?>
        <button>ingresar</button>

      </form>
    </div>

  </div>
  <!-- <script src='js/jquery.min.js'></script>

  <script src="js/index.js"></script> -->

</body>

</html>