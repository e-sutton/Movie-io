<?php
/*
* login.php *
* Rev 1 *
* 08/03/17 *
*
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
          ':movie_title' => $_POST['title']
      );

      //Insert
      $query = "
      INSERT INTO movie_reviews(
        user_id,
        score,
        review,
        release_date,
        movie_title
        )
        VALUES(
          :user_id,
          :score,
          :review,
          :release_date,
          :movie_title
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
