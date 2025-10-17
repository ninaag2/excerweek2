<?php
try{
    include 'includes/DatabaseConnection.php';
    
    $sql = 'DELETE FROM jokes WHERE id = :id';
    $stmt = $pdo->prepare($sql);    
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    header('Location: jokes.php');
}catch(PDOException $e){
$title = 'An error has occurred';
$output = ' Unable to delete joke. Database error: ' . $e->getMessage();

}
include 'templates/layout.html.php';
?>