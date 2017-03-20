<?php
/*
* login.php *
* Rev 1 *
* 08/02/17 *
*
*/
    // get connection to DB
    include("connection.php");
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ini_set('error_log', 'path_to_log_file');
    ini_set('log_errors_max_len', 0);
    ini_set('log_errors', true);
    $login_ok = true;

    // check if edit form submitted
    if(!empty($_POST))
    {

    // If the user entered a new password, we need to hash it and generate a fresh salt
    /*if(!empty($_POST['password']))
    {
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        $password = hash('sha256', $_POST['password'] . $salt);
        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }
    }
    else
    {
        // If the user did not enter a new password we will not update their old one.
        $password = null;
        $salt = null;
    }*/

    // Initial query parameter values
    $query_params = array();

    if(!empty($_POST['email']))
  {
    $query_params[':email'] = $_POST['email'];
  }


    // If the user is changing their password, then we need parameter values
    // for the new password hash and salt too.
    /*if($password !== null)
    {
        $query_params[':password'] = $password;
        $query_params[':salt'] = $salt;
    }*/

    $query_params[':sessionuser'] = $_SESSION['user']['username'];

    if(!empty($_POST['username']))
  {
    $query_params[':username'] = $_POST['username'];
  }

    if(!empty($_POST['location']))
  {
    $query_params[':location'] = $_POST['location'];
  }

  if(!empty($_POST['about_me']))
  {
    $query_params[':about_me'] = $_POST['about_me'];
  }

    // first half of query
    $query = "
        UPDATE login
        SET
        col1 = ''
    ";

    // if user changing password, include the below
    /*if($password !== null)
    {
        $query .= "
            , password = :password
            , salt = :salt
        ";
    }*/

    if(!empty($_POST['username']))
  {
      $query .="
          , username = :username
      ";
  }


    if(!empty($_POST['about_me']))
  {
      $query .="
          , about_me = :about_me
      ";
  }

  if(!empty($_POST['email']))
{
    $query .="
        , email = :email
    ";
}

if(!empty($_POST['location']))
{
  $query .="
      , location = :location
  ";
}



    // finish query
    $query .= "
        WHERE
            username = :sessionuser
    ";

    try
    {
        // Run query
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {

        echo("Failed to run query: " . $ex->getMessage());
        $login_ok = false;
    }





    // update session info with new email
    $_SESSION['user']['email'] = $_POST['email'];

        if($login_ok)
        {

            $result = "Updated!";
            echo json_encode($result);
        }
        else
        {

            $result = "Update failed!";
            echo json_encode($result);

        }
  }

  //now upload file if file exists
  if(isset($_FILES['myfile']) && $_FILES['myfile']['size'] > 0) {
    $name = $_FILES['myfile']['name'];
    //$tmpName = addslashes(file_get_contents($_FILES['myfile']['tmp_name']));
    $tmpName = $_FILES['myfile']['tmp_name'];
    $fileSize = $_FILES['myfile']['size'];
    $fileType = $_FILES['myfile']['type'];

    /*$myFile = fopen($tmpName,'r');
    $readFile = fread($myfile, filesize($tmpName));
    $readFile = addslashes($readFile);
    fclose($myFile);*/

    $username = $_SESSION['user']['username'];

    //upload file to database/server
    $pathName = "images/" . $name;
    move_uploaded_file($tmpName,$pathName);


    $query = "
    UPDATE login SET avatar = '$pathName', filetype = '$fileType', filesize = '$fileSize', filename = '$name'
    WHERE username = '$username'
    ";

    $login_ok = true;

    try
    {
        // Run query
        $stmt = $db->prepare($query);
        $result = $stmt->execute();
    }
    catch(PDOException $ex)
    {

        echo("Failed to run query: " . $ex->getMessage());
        $login_ok = false;
    }

    if($login_ok)
    {

        $result = "Updated!";
        echo json_encode($result);
    }
    else
    {

        $result = "Update failed!";
        echo json_encode($result);

    }


  }

?>
