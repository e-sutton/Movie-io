<?php
/*
* login.php *
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
    if(!empty($_POST))
    {
      // parameters
      $query_params = array(
          ':movie_title' => $_POST['title']
      );

      //Insert
      $query = "
      SELECT a.review, a.score, b.username, b.location, b.avatar, b.id, a.date from movie_reviews a
      JOIN login b on (a.user_id = b.id)
        WHERE
        a.movie_title = :movie_title
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
