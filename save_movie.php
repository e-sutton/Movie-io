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
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ini_set('error_log', 'path_to_log_file');
    ini_set('log_errors_max_len', 0);
    ini_set('log_errors', true);

    if(!empty($_POST))
    {
      //variables
      $all_ok = true;
      $saved = false;

      //check if movie already saved
      $query = "
          SELECT
              1
          FROM movies
          WHERE
              title = :movie_title
      ";

      $query_params = array(
          ':movie_title' => $_POST['title']
      );

      try
      {
          // query the database
          $stmt = $db->prepare($query);
          $result = $stmt->execute($query_params);
      }
      catch(PDOException $ex)
      {
          echo("Failed to run query: " . $ex->getMessage());
      }

      // fetch returns an array representing the next row or false for no rows
      $row = $stmt->fetch();

      // If a row is returned, then movie already saved
      if($row)
      {
          echo("Movie already saved!");
          $saved = true;
      }

      //if not saved already, save movie to db
      if (!$saved)
      {

      $all_ok = true;
      // parameters
      $query_params = array(
          ':release_date' => $_POST['release_date'],
          ':movie_title' => $_POST['title'],
          ':synopsis' => $_POST['synopsis'],
          ':starring' => $_POST['starring'],
          ':awards' => $_POST['awards'],
          ':metascore' => $_POST['metascore'],
          ':poster' => $_POST['poster']
      );

      //Insert
      $query = "
      INSERT INTO movies(
        synopsis,
        starring,
        awards,
        metascore,
        poster,
        release_date,
        title
        )
        VALUES(
          :synopsis,
          :starring,
          :awards,
          :metascore,
          :poster,
          :release_date,
          :movie_title
          )
      ";

      try
      {
          // run query
          $stmt = $db->prepare($query);
          $result = $stmt->execute($query_params);
          $last_id = $db->lastInsertId();;
      }
      catch(PDOException $ex)
      {

          echo("Failed to run query: " . $ex->getMessage());
          $all_ok = false;
      }

      if($all_ok)
      {

          $result = "Posted movie data!";
          echo ($last_id);
      }
      else
      {

          $result = "Post of movie data failed!";
          echo json_encode($result);

      }
}

}
?>
