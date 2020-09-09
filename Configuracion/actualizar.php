<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$id = $monto = $leyenda = "";


// Processing form data when form is submitted
if(isset($_GET["Monto"])&& isset($_GET["Leyenda"])){
    // Get hidden input value
    $monto = $_GET["Monto"];
    $leyenda = $_GET["Leyenda"];

    // Check input errors before inserting in database
        $sql = "UPDATE configuracion SET MontoEnvio=?,Leyenda=? ";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $p1,$p2);

            // Set parameters
            $p1 = $monto;
            $p2 = $leyenda;
    
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                echo "";
            } else{
                echo "Ocurrió un error.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
}
?>
