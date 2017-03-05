<?php
/*
* logout.php *
*
* @reference http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html *
*/

    require("connection.php");

    // Remove user data from session
    unset($_SESSION['user']);

    //destroy session
    session_destroy();

    // Redirect to index
    header("Location: index.php");
    die("Redirecting to: index.php");
?>
