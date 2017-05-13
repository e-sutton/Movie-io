<?php
/*
* get_password.php *
* Rev 1 *
* 01/05/17 *
* @author: Eoin Sutton
*/
    // get connection to DB
    include("connection.php");

         $query = "
            SELECT
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

        $all_ok = false;

        // if row exists, user exists
        $row = $stmt->fetch();
        if($row)
        {
            //first save email
            $usermail = $row['email'];
            //reset password
            $newPass = rand(5, 9999);
            //new salt
            $newSalt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

            // Hash password with salt value
            $password = hash('sha256', $newPass . $newSalt);

            // Hash the hash value 65536 more times to protect against brute force attacks
            for($round = 0; $round < 65536; $round++)
            {
                $password = hash('sha256', $password . $newSalt);
            }

            //insert new password to DB
            $query2 = "
                UPDATE login SET
                password = :password,
                salt = :newSalt
                WHERE
                username = :username
            ";

            // query parameters for main query
            $query_params2 = array(
                ':username' => $_POST['username'],
                ':password' => $password,
                ':newSalt' => $newSalt
            );

            try
            {
                // execute query
                $stmt2 = $db->prepare($query2);
                $result2 = $stmt2->execute($query_params2);
                $all_ok = true;
            }
            catch(PDOException $ex)
            {
                echo("Failed to run query: " . $ex->getMessage());
            }


        }

        if($all_ok)
        {

            //send email
            $msg = "
            <html>
            <head></head>
            <body>
            Hi,
            <br/>
            You recently requested a password reset for Movie-IO.
            <br/>
            Please see your new password below:
            <br/>
            Username: ". $_POST['username'] ."
            <br/>
            Password: " . $newPass ."
            <br/>
            Please use this password to login to Movie-IO.
            <br/>
            https://movie-io.byethost7.com/index.php
            ";

            $msg = wordwrap($msg,70);
            $subject = "Your password has been reset for Movie-IO";
            $to = $usermail;

            $headers = "MIME-Version: 1.0" . "\n";
            $headers .= "Reply-To: noreply@movie-io.byethost7.com" . "\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\n";
            $headers .= "From: <noreply@movie-io.byethost7.com>" . "\n";

            //send mail
            mail($to,$subject,$msg,$headers);

            $result = "Reset password worked";
            echo json_encode($result);

        }
        else
        {

            $result = "Reset password/email send failed";
            echo json_encode($result);

        }

?>
