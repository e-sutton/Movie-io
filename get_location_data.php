<?php
/*
* get_location_data.php *
* Rev 1 *
* 27/03/17 *
*
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
      SELECT * from movie_reviews
      WHERE lat is not null
      AND lng is not null
      AND score >= 3
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
