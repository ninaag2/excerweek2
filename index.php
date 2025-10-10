<?php
$jokes = [];
$error = '';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=week4;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT joketext, jokedate, image FROM jokes ORDER BY jokedate DESC';
    $result = $pdo->query($sql);
    $jokes = $result->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = 'Unable to connect to the database: ' . $e->getMessage();
}

include 'templates/joke.html.php';
?>