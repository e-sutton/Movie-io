<?php
/*
* load_mylist.php
* Rev 1
* 08/03/17
* @author: Eoin Sutton
*/
    // get connection to DB
    include("connection.php");

    //debug
    function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    };

      //start
      if(!empty($_POST))
      {
        // parameters
        $query_params = array(
            ':id' => $_POST['id']
        );


        //query
        $query = "
        SELECT a.movie_title, a.release_date, a.review, a.score, b.id
        FROM movie_reviews a join movies b on (a.movie_title = b.title)
        WHERE user_id = :id
        ORDER BY a.score desc
        ";

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

        $encode = array();
        while ($row = $stmt->fetch()) {
          $encode[] = $row;
        };

        echo json_encode($encode);


      }
      else{


      // parameters
      $query_params = array(
          ':id' => $_SESSION['user']['id']
      );


      //query
      $query = "
      SELECT a.movie_title, a.release_date, a.review, a.score, b.id
      FROM movie_reviews a join movies b on (a.movie_title = b.title)
      WHERE user_id = :id
      ORDER BY a.score desc
      ";

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

      $encode = array();
      while ($row = $stmt->fetch()) {
        $encode[] = $row;
      };

      echo json_encode($encode);

      /*if($row)
      {
        echo json_encode($row);
      }*/
    }


?>
