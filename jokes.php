<?php
try {
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunction.php';
    
    // Check if there's a search term
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $authorId = isset($_GET['author']) ? (int)$_GET['author'] : 0;
    
    // Get jokes based on filters
    if (!empty($searchTerm)) {
        $jokes = searchJokes($pdo, $searchTerm);
        $title = 'Search Results for: ' . htmlspecialchars($searchTerm);
    } elseif ($categoryId > 0) {
        $jokes = getJokesByCategory($pdo, $categoryId);
        $category = getCategoryById($pdo, $categoryId);
        $title = 'Jokes in Category: ' . htmlspecialchars($category['categoryName']);
    } elseif ($authorId > 0) {
        $jokes = getJokesByAuthor($pdo, $authorId);
        $author = getAuthorById($pdo, $authorId);
        $title = 'Jokes by: ' . htmlspecialchars($author['name']);
    } else {
        $jokes = getAllJokes($pdo);
        $title = 'Joke List';
    }
    
    // Get total jokes count
    $totalJokes = totalJokes($pdo);
    
    // Get all categories and authors for filter options
    $categories = getAllCategories($pdo);
    $authors = getAllAuthors($pdo);

    ob_start();
    include 'templates/jokes.html.php';
    $output = ob_get_clean();

} catch(PDOException $e) {
    $title = 'Database Error';
    $output = 'Database error: ' . htmlspecialchars($e->getMessage());
} catch(Exception $e) {
    $title = 'Error';
    $output = 'Error: ' . htmlspecialchars($e->getMessage());
}

include 'templates/layout.html.php';
?>
