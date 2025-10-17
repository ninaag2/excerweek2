<?php
try{
    include 'includes/DatabaseConnection.php';

    $sql = 'SELECT * FROM jokes';
    $jokes = $pdo->query($sql);
    $title ='joke list';

    ob_clean();
    include 'templates/jokes.html.php';
    $output = ob_get_clean();

}catch(PDOException $e){
    $title = 'An error has occurred';
    $output = 'Database error:' .$e->getMessage();
}
include 'templates/layout.html.php';

?>
