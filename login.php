<?php
/*
* login.php *
* Rev 1 *
* 08/02/17 *
*
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
                email
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

        // This variable tells us whether the user has successfully logged in or not.
        // We initialize it to false, assuming they have not.
        // If we determine that they have entered the right details, then we switch it to true.
        $login_ok = false;

        // Retrieve the user data from the database.  If $row is false, then the username
        // they entered is not registered.
        $row = $stmt->fetch();
        if($row)
        {
            // Using the password submitted by the user and the salt stored in the database,
            // we now check to see whether the passwords match by hashing the submitted password
            // and comparing it to the hashed version already stored in the database.
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
            //but first remove sensitive data from it
            unset($row['salt']);
            unset($row['password']);
            $_SESSION['user'] = $row;
            // Return successful message is login is successfulse
            $result = "Logged In";
            echo json_encode($result);
        }
        else
        {

            $result = "Login Failed: Invalid Details";
            echo json_encode($result);

        }

?>
