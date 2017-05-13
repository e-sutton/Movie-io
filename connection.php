<?php
/*
* connection.php 
* Rev 1
* 08/02/17
* @author: Eoin Sutton
* @reference http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html *
*/
//variables
$username = "root";
$password = "root";
$host = "localhost";
$dbname = "Movieio";


try{
    $db = new PDO("mysql:host={$host}; port=8889; dbname={$dbname};charset=UTF8", $username, $password);
} catch (PDOException $ex) {
    die("Failed to connect to the database: " . $ex->getMessage());
}
//throw exceptions for errors
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//return db rows using associative array
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//initialise session
session_start();
?>
