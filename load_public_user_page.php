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

      $query_params = array(
      ':userid' => $_POST['userid']
      );

    $query = "
    SELECT id, username, email, about_me, location, avatar
    FROM login
    WHERE id = :userid
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

    $row = $stmt->fetch();
    //save user data to session variables
    $_SESSION['publicuser'] = $row['username'];
    $_SESSION['publicuserid'] = $row['id'];
    $_SESSION['publicuseremail'] = $row['email'];
    $_SESSION['publicuserabout'] = $row['about_me'];
    $_SESSION['publicuserlocation'] = $row['location'];
    $_SESSION['publicuseravatar'] = $row['avatar'];


        if($login_ok)
        {

          echo json_encode(array(
            "username" => $row['username'],
            "email" => $row['email'],
            "about_me" => $row['about_me'],
            "location" => $row['location'],
            "avatar" => $row['avatar'],
            "id" => $row['id']
          ));
        }
        else
        {

            $result = "Fetch of public user data failed!";
            echo json_encode($result);

        }
  }


?>
