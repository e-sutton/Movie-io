<?php
/*
* get_location_data.php
* Rev 1
* 27/03/17
* @ author: Eoin Sutton
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

      $query = "
      SELECT a.movie_id, a.movie_title, a.score, a.review, a.date, a.lat, a.lng, b.username
      from movie_reviews a
      JOIN login b on (a.user_id = b.id)
      WHERE a.lat is not null
      AND a.lng is not null
      AND a.score >= 3
      ORDER BY a.date desc
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



?>
