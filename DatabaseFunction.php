<?php
function totalJokes($pdo) {
    $query = query($pdo,'SELECT COUNT(*) FROM jokes');
    $row = $query->fetch();
    return $row[0];
}
function query ($pdo, $sql, $parameters = [])
{
     if (empty($parameters)) {
          $query = $pdo->query($sql);
          $query->execute();
     } else {
          $query = $pdo->prepare($sql);
          $query->execute($parameters);
     }
     return $query ;
}
function getjokes($pdo,$id) {
      $parameters = [':id' => $id];
      $query = query($pdo,'SELECT * FROM jokes WHERE id = :id', $parameters);
      return $query->fetch();
}
function updatejokes($pdo, $id, $joketext) {
       $query = 'update jokes  set joketext = :joketext where id = :id';
         $parameters = [
              ':joketext' => $joketext,
              ':id' => $id ];
        query($pdo, $query, $parameters);
}
?>