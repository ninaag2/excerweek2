<?php
try{
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunction.php';
    $sql = 'SELECT jokes.id, jokes.joketext, author.name, author.email, category.categoryName 
        FROM jokes
        INNER JOIN author ON jokes.authorid = author.id
        INNER JOIN category ON jokes.categoryid = category.id';

    $jokes = $pdo->query($sql);
    #$sql = 'SELECT * FROM jokes';
    #$jokes = $pdo->query($sql);
    $title ='joke list';
    $totalJokes = totalJokes($pdo);

    ob_start();
    include 'templates/jokes.html.php';
    $output = ob_get_clean();

}catch(PDOException $e){
    $title = 'An error has occurred';
    $output = 'Database error:' .$e->getMessage();
}
include 'templates/layout.html.php';

?>
