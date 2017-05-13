<?php
/*
* login.php *
* Rev 1 *
* 08/02/17 *
* @author: Eoin Sutton
* @reference http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html *
*/
    // get connection to DB
    include("connection.php");

         $query = "
            SELECT
                id,
                username,
                password,
                salt,
                email,
                about_me,
                location,
                avatar
            FROM login
            WHERE
                username = :username
        ";

        // parameter
        $query_params = array(
            ':username' => $_POST['username']
        );

        try
        {
            // run query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {

            echo("Failed to run query: " . $ex->getMessage());
        }

        $login_ok = false;

        // Get data from DB, if row false, no data returned
        $row = $stmt->fetch();
        if($row)
        {
            //check password matches
            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }

            if($check_password === $row['password'] && $row['username'] === $_POST['username'])
            {
                // If they do, then we flip this to true
                $login_ok = true;
                //get user id
                $userid = $row['id'];
            }

        }

        if($login_ok)
        {

            // This stores the user's data into the session at the index 'user'.
            // but first remove sensitive data from it
            unset($row['salt']);
            unset($row['password']);
            $_SESSION['user'] = $row;
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['about_me'] = $row['about_me'];
            $_SESSION['location'] = $row['location'];
            $_SESSION['avatar'] = $row['avatar'];

            echo json_encode(array(
              "username" => $row['username'],
              "email" => $row['email'],
              "about_me" => $row['about_me'],
              "location" => $row['location'],
              "avatar" => str_replace(' ', '%20', $row['avatar']),
              "id" => $row['id']
            ));

        }
        else
        {

            $result = "Login Failed: Invalid Details";
            echo json_encode($result);

        }

?>
