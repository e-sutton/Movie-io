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
      //variables
      $all_ok = true;

      // parameters
      $query_params = array(
          ':id' => $_POST['id']
      );

      //Insert
      $query = "
      SELECT title, synopsis, release_date, starring, awards, metascore, poster
      FROM movies
      WHERE id = :id
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
          $all_ok = false;
      }

      //get data
      $row = $stmt->fetch();

      $title = $row['title'];
      $release_date = $row['release_date'];
      $plot = $row['synopsis'];
      $actors = $row['starring'];
      $awards = $row['awards'];
      $metascore = $row['metascore'];
      $poster = $row['poster'];

      if($all_ok)
      {

          echo json_encode(array(
            "Title" => $title,
            "Released" => $release_date,
            "Plot" => $plot,
            "Actors" => $actors,
            "Awards" => $awards,
            "Metascore" => $metascore,
            "Poster" => $poster
          ));
      }
      else
      {

          $result = "Fetch of movie data for user list failed!";
          echo json_encode($result);

      }



}
?>
