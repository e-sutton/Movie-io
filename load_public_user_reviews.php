<?php
/*
* load_user_reviews.php *
* Rev 1 *
* 08/03/17 *
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
      // parameters
      $query_params = array(
          ':id' => $_SESSION['publicuserid']
      );


      //query
      $query = "
      SELECT b.id, b.avatar, b.username, b.location, a.review, a.score, a.date
      FROM user_reviews a JOIN login b on (a.created_by_user_id = b.id)
      WHERE a.user_id = :id
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
