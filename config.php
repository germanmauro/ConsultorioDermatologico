<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

/*LOCAL*/
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'ger');
define('DB_PASSWORD', '123');
define('DB_NAME', 'consultorio');
/*PROD*/
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'c1701132_derma');
// define('DB_PASSWORD', 'fesotuRI27');
// define('DB_NAME', 'c1701132_derma');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
