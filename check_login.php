<?php
/*
* check_login.php
* Rev 1
* 08/02/17
* @author: Eoin Sutton
*/

//check if user logged in

// get connection to DB
include("connection.php");

// Check whether user is logged in
if(empty($_SESSION['user']))
{
  echo 'Not Logged In';
}
else
{
    echo 'Logged In';
}

?>
