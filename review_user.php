<?php
/*
* review_user.php
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
          ':created_by_user_id' => $_POST['created_by_user_id'],
          ':score' => $_POST['score'],
          ':review' => $_POST['review'],
          ':user_id' => $_POST['user_id']
      );

      //Insert
      $query = "
      INSERT INTO user_reviews(
        user_id,
        created_by_user_id,
        score,
        review,
        date
        )
        VALUES(
          :user_id,
          :created_by_user_id,
          :score,
          :review,
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
