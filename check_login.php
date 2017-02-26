<?php

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
