<?php
/*
* register.php *
* Rev 1 *
* 08/02/17 *
*
* @reference http://forums.devshed.com/php-faqs-stickies-167/program-basic-secure-login-system-using-php-mysql-891201.html *
*/ 
    // get connection to DB
    include("connection.php"); 

    // SQL query to check if username already taken 
        $query = " 
            SELECT 
                1 
            FROM login 
            WHERE 
                username = :username 
        "; 
         
        // this is the token for the username - this is used to 
        // prevent sql injection attacks.
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
         
        // fetch returns an array representing the next row or false for no rows
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
         
        // This hashes the password with the salt for security
        $password = hash('sha256', $_POST['password'] . $salt); 
         
        // Hash the hash value 65536 more times to protect against brute force attacks
        for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
         
        // Here we prepare our tokens for insertion into the SQL query.  We do not 
        // store the original password; only the hashed version of it. 
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            // Execute the query to create the user 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 

        } 
        catch(PDOException $ex) 
        {  
            echo("Failed to run query: " . $ex->getMessage()); 
        } 




?>