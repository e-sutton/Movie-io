<?php
/*
* logout.php
* @author: Eoin Sutton
*/

    require("connection.php");

    // Remove user data from session
    unset($_SESSION['user']);

    //destroy session stuff
    session_destroy();

    // Redirect user to index
    header("Location: index.php");
    die("Redirecting to: index.php");
?>
