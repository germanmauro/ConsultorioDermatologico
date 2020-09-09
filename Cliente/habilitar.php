<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$id = $monto = "";


// Processing form data when form is submitted
if(isset($_GET["User"])){
    // Get hidden input value
    $user = $_GET["User"];

    // Check input errors before inserting in database
        $sql = "UPDATE usuario SET Baja='False' where User=? ";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $p1);

            // Set parameters
            $p1 = $user;
    
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
