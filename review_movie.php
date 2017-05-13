<?php
/*
* review_movie.php
* Rev 1
* 20/04/17
* @author: Eoin Sutton
*/
    // get connection to DB
    include("connection.php");

    if(!empty($_POST))
    {
      // parameters
      $query_params = array(
          ':user_id' => $_POST['user_id'],
          ':score' => $_POST['score'],
          ':review' => $_POST['review'],
          ':release_date' => $_POST['release_date'],
          ':movie_title' => $_POST['title'],
          ':movie_id' => $_POST['movie_id'],
          ':lat' => filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT),
          ':lng' => filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT)
      );

      //Insert
      $query = "
      INSERT INTO movie_reviews(
        user_id,
        score,
        review,
        release_date,
        movie_title,
        movie_id,
        lat,
        lng,
        date
        )
        VALUES(
          :user_id,
          :score,
          :review,
          :release_date,
          :movie_title,
          :movie_id,
          :lat,
          :lng,
          now()
          )
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


}
?>
