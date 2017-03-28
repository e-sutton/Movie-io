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
      SELECT a.title, a.synopsis, a.release_date, a.starring, a.awards, a.metascore, a.poster,
      b.id, b.user_id, b.score, b.review, b.movie_title
      FROM movies a join movie_reviews b on (a.title = b.movie_title)
      WHERE a.id = :id
      ";

      //try
      /*
      SELECT a.title, a.synopsis, a.release_date, a.starring, a.awards, a.metascore, a.poster,
b.id, b.user_id, b.score, b.review, b.movie_title
      FROM movies a join movie_reviews b on (a.title = b.movie_title)
      WHERE a.id = 10
      */

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
      $id = $row['id'];
      $user_id = $row['user_id'];
      $score = $row['score'];
      $review = $row['review'];
      $movie_title = $row['movie_title'];

      if($all_ok)
      {

          echo json_encode(array(
            "Title" => $title,
            "Released" => $release_date,
            "Plot" => $plot,
            "Actors" => $actors,
            "Awards" => $awards,
            "Metascore" => $metascore,
            "Poster" => $poster,
            "id" => $id,
            "user_id" => $user_id,
            "score" => $score,
            "review" => $review,
            "movie_title" => $movie_title
          ));
      }
      else
      {

          $result = "Fetch of movie data for user list failed!";
          echo json_encode($result);

      }



}
?>
