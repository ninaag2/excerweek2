<?php
if(isset($_POST['joketext'])){
    try{
        include 'includes/DatabaseConnection.php';
         $imagePath = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['image']['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);

            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
            if (isset($allowed[$mime])) {
                $ext = $allowed[$mime];
                $uploadDir = __DIR__ . '/image/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $filename = uniqid('img_', true) . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $filename)) {
                    $imagePath = 'image/' . $filename; // lưu đường dẫn relative vào DB
                }
            }
        }

        
        
        $sql = 'INSERT INTO jokes SET
        joketext = :joketext,
         image = :image,
        jokedate = CURDATE()';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':joketext', $_POST['joketext']);
       if ($imagePath !== null) {
            $stmt->bindValue(':image', $imagePath);
        } else {
            $stmt->bindValue(':image', null, PDO::PARAM_NULL);
        }
        $stmt->execute();
        header('Location: jokes.php');
    }catch(PDOException $e){
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage();
       
    }
} else {
    $title = 'Add a Joke';
    ob_start();
    include 'templates/addjoke.html.php';
    $output = ob_get_clean();
    include 'templates/layout.html.php';
}
?>