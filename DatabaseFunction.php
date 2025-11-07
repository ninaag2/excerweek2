<?php
// ========== CORE QUERY FUNCTION ==========
function query($pdo, $sql, $parameters = [])
{
    if (empty($parameters)) {
        $query = $pdo->query($sql);
        $query->execute();
    } else {
        $query = $pdo->prepare($sql);
        $query->execute($parameters);
    }
    return $query;
}

// ========== JOKES FUNCTIONS ==========
function getAllJokes($pdo) {
    $sql = 'SELECT j.*, a.name as author_name, c.categoryName as category_name 
            FROM jokes j 
            LEFT JOIN author a ON j.authorid = a.id 
            LEFT JOIN category c ON j.categoryid = c.id 
            ORDER BY j.jokedate DESC';
    return query($pdo, $sql);
}

function totalJokes($pdo) {
    $query = query($pdo, 'SELECT COUNT(*) FROM jokes');
    $row = $query->fetch();
    return $row[0];
}

function getJokeById($pdo, $id) {
    $parameters = [':id' => $id];
    $query = query($pdo, 'SELECT * FROM jokes WHERE id = :id', $parameters);
    return $query->fetch();
}

function addJoke($pdo, $joketext, $authorid, $categoryid, $imagePath = null) {
    $sql = 'INSERT INTO jokes (joketext, image, jokedate, authorid, categoryid) 
            VALUES (:joketext, :image, CURDATE(), :authorid, :categoryid)';
    $parameters = [
        ':joketext' => $joketext,
        ':authorid' => $authorid,
        ':categoryid' => $categoryid,
        ':image' => $imagePath
    ];
    query($pdo, $sql, $parameters);
}

function updateJoke($pdo, $id, $joketext, $authorid, $categoryid, $imagePath = null) {
    if ($imagePath !== null) {
        $sql = 'UPDATE jokes SET joketext = :joketext, authorid = :authorid, 
                categoryid = :categoryid, image = :image WHERE id = :id';
        $parameters = [
            ':joketext' => $joketext,
            ':authorid' => $authorid,
            ':categoryid' => $categoryid,
            ':image' => $imagePath,
            ':id' => $id
        ];
    } else {
        $sql = 'UPDATE jokes SET joketext = :joketext, authorid = :authorid, 
                categoryid = :categoryid WHERE id = :id';
        $parameters = [
            ':joketext' => $joketext,
            ':authorid' => $authorid,
            ':categoryid' => $categoryid,
            ':id' => $id
        ];
    }
    query($pdo, $sql, $parameters);
}

function deleteJoke($pdo, $id) {
    // Get image path before deleting to remove file
    $joke = getJokeById($pdo, $id);
    if ($joke && !empty($joke['image'])) {
        $imagePath = __DIR__ . '/../' . $joke['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    $parameters = [':id' => $id];
    query($pdo, 'DELETE FROM jokes WHERE id = :id', $parameters);
}

// ========== AUTHOR FUNCTIONS ==========
function getAllAuthors($pdo) {
    return query($pdo, 'SELECT * FROM author ORDER BY name');
}

function getAuthorById($pdo, $id) {
    $parameters = [':id' => $id];
    $query = query($pdo, 'SELECT * FROM author WHERE id = :id', $parameters);
    return $query->fetch();
}

function addAuthor($pdo, $name, $email = null) {
    $sql = 'INSERT INTO author (name, email) VALUES (:name, :email)';
    $parameters = [
        ':name' => $name,
        ':email' => $email
    ];
    query($pdo, $sql, $parameters);
}

function updateAuthor($pdo, $id, $name, $email = null) {
    $sql = 'UPDATE author SET name = :name, email = :email WHERE id = :id';
    $parameters = [
        ':name' => $name,
        ':email' => $email,
        ':id' => $id
    ];
    query($pdo, $sql, $parameters);
}

function deleteAuthor($pdo, $id) {
    $parameters = [':id' => $id];
    query($pdo, 'DELETE FROM author WHERE id = :id', $parameters);
}

// ========== CATEGORY FUNCTIONS ==========
function getAllCategories($pdo) {
    return query($pdo, 'SELECT * FROM category ORDER BY categoryName');
}

function getCategoryById($pdo, $id) {
    $parameters = [':id' => $id];
    $query = query($pdo, 'SELECT * FROM category WHERE id = :id', $parameters);
    return $query->fetch();
}

function addCategory($pdo, $categoryName, $description = null) {
    $sql = 'INSERT INTO category (categoryName, description) VALUES (:categoryName, :description)';
    $parameters = [
        ':categoryName' => $categoryName,
        ':description' => $description
    ];
    query($pdo, $sql, $parameters);
}

function updateCategory($pdo, $id, $categoryName, $description = null) {
    $sql = 'UPDATE category SET categoryName = :categoryName, description = :description WHERE id = :id';
    $parameters = [
        ':categoryName' => $categoryName,
        ':description' => $description,
        ':id' => $id
    ];
    query($pdo, $sql, $parameters);
}

function deleteCategory($pdo, $id) {
    $parameters = [':id' => $id];
    query($pdo, 'DELETE FROM category WHERE id = :id', $parameters);
}

// ========== SEARCH AND FILTER FUNCTIONS ==========
function searchJokes($pdo, $searchTerm) {
    $sql = 'SELECT j.*, a.name as author_name, c.categoryName as category_name 
            FROM jokes j 
            LEFT JOIN author a ON j.authorid = a.id 
            LEFT JOIN category c ON j.categoryid = c.id 
            WHERE j.joketext LIKE :search 
            OR a.name LIKE :search 
            OR c.categoryName LIKE :search 
            ORDER BY j.jokedate DESC';
    $parameters = [':search' => '%' . $searchTerm . '%'];
    return query($pdo, $sql, $parameters);
}

function getJokesByCategory($pdo, $categoryId) {
    $sql = 'SELECT j.*, a.name as author_name, c.categoryName as category_name 
            FROM jokes j 
            LEFT JOIN author a ON j.authorid = a.id 
            LEFT JOIN category c ON j.categoryid = c.id 
            WHERE j.categoryid = :categoryid 
            ORDER BY j.jokedate DESC';
    $parameters = [':categoryid' => $categoryId];
    return query($pdo, $sql, $parameters);
}

function getJokesByAuthor($pdo, $authorId) {
    $sql = 'SELECT j.*, a.name as author_name, c.categoryName as category_name 
            FROM jokes j 
            LEFT JOIN author a ON j.authorid = a.id 
            LEFT JOIN category c ON j.categoryid = c.id 
            WHERE j.authorid = :authorid 
            ORDER BY j.jokedate DESC';
    $parameters = [':authorid' => $authorId];
    return query($pdo, $sql, $parameters);
}

// ========== UTILITY FUNCTIONS ==========
function handleImageUpload($file) {
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmp = $file['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);

    $allowed = [
        'image/jpeg' => 'jpg', 
        'image/png' => 'png', 
        'image/gif' => 'gif'
    ];

    if (!isset($allowed[$mime])) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
    }

    $ext = $allowed[$mime];
    $uploadDir = __DIR__ . '/../image/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = uniqid('img_', true) . '.' . $ext;
    if (move_uploaded_file($tmp, $uploadDir . $filename)) {
        return 'image/' . $filename;
    }

    throw new Exception('Failed to upload image.');
}

function deleteImageFile($imagePath) {
    if (!empty($imagePath)) {
        $fullPath = __DIR__ . '/../' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}

// ========== VALIDATION FUNCTIONS ==========
function validateJoke($joketext, $authorid, $categoryid) {
    $errors = [];

    if (empty(trim($joketext))) {
        $errors[] = 'Joke text is required.';
    }

    if (empty($authorid) || !is_numeric($authorid)) {
        $errors[] = 'Please select a valid author.';
    }

    if (empty($categoryid) || !is_numeric($categoryid)) {
        $errors[] = 'Please select a valid category.';
    }

    return $errors;
}

function validateAuthor($name, $email = null) {
    $errors = [];

    if (empty(trim($name))) {
        $errors[] = 'Author name is required.';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    return $errors;
}

function validateCategory($categoryName) {
    $errors = [];

    if (empty(trim($categoryName))) {
        $errors[] = 'Category name is required.';
    }

    return $errors;
}

// ========== LEGACY FUNCTION ALIASES (for backward compatibility) ==========
function addjokes($pdo, $joketext, $authorid, $categoryid, $imagePath = null) {
    return addJoke($pdo, $joketext, $authorid, $categoryid, $imagePath);
}

function getjokes($pdo, $id) {
    return getJokeById($pdo, $id);
}

function updatejokes($pdo, $id, $joketext) {
    // This is a simplified version - consider using updateJoke instead
    $sql = 'UPDATE jokes SET joketext = :joketext WHERE id = :id';
    $parameters = [
        ':joketext' => $joketext,
        ':id' => $id
    ];
    query($pdo, $sql, $parameters);
}

function deletejokes($pdo, $id) {
    return deleteJoke($pdo, $id);
}
?>