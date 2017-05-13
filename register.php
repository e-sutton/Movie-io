<?php
/*
* register.php *
* Rev 1 *
* 08/02/17 *
* @author: Eoin Sutton
* @reference http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html *
*/
    // get connection to DB
    include("connection.php");

    // Check if name already taken
        $query = "
            SELECT
                1
            FROM login
            WHERE
                username = :username
        ";

        // query parameter for username
        $query_params = array(
            ':username' => $_POST['username']
        );

        try
        {
            // query the database
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            echo("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();

        // If a row is returned, then the email is in use
        if($row)
        {
            echo("This username is already in use");
        }

        // check same for email
        $query = "
            SELECT
                1
            FROM login
            WHERE
                email = :email
        ";

        $query_params = array(
            ':email' => $_POST['email']
        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            echo("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();

        if($row)
        {
            echo("This email address is already registered");
        }

         // Insert details into DB
        $query = "
            INSERT INTO login (
                username,
                password,
                salt,
                email
            ) VALUES (
                :username,
                :password,
                :salt,
                :email
            )
        ";

        // generate salt to help with hashing password
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

        // Hash password with salt value
        $password = hash('sha256', $_POST['password'] . $salt);

        // Hash the hash value 65536 more times to protect against brute force attacks
        for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }

        // query parameters for main query
        $query_params = array(
            ':username' => $_POST['username'],
            ':password' => $password,
            ':salt' => $salt,
            ':email' => $_POST['email']
        );

        try
        {
            // execute query
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
            //send email
            $msg = "
            <html>
            <head></head>
            <body>
            Hi,
            <br/>
            Thank you for registering for Movie-IO.
            <br/>
            Please see your login details below:
            <br/>
            Username: ". $_POST['username'] ."
            <br/>
            Password: " . $_POST['password'] ."
            <br/>
            Please use this password to login to Movie-IO.
            <br/>
            https://movie-io.byethost7.com/index.php
            </body>
            </html>
            ";

            $msg = wordwrap($msg,70);
            $subject = "Movie-IO Registration Details";
            $to = $_POST['email'];

            $headers = "MIME-Version: 1.0" . "\n";
            $headers .= "Reply-To: noreply@movie-io.byethost7.com" . "\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\n";
            $headers .= "From: <noreply@movie-io.byethost7.com>" . "\n";

            //send mail
            mail($to,$subject,$msg,$headers);


        }
        catch(PDOException $ex)
        {
            echo("Failed to run query: " . $ex->getMessage());
        }




?>
