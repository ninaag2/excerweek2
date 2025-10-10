<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of jokes</title>
    <style>
        .joke-item {
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .joke-image {
            max-width: 200px;
            height: auto;
            margin-right: 15px;
            float: left;
        }
        .joke-content {
            overflow: hidden;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <h1>List of Jokes</h1>
    
    <?php if(!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php elseif(isset($jokes) && !empty($jokes)): ?>
        <?php foreach($jokes as $joke): ?>
            <div class="joke-item clearfix">
                <?php if(!empty($joke['image'])): ?>
                    <img src="<?= 'images/' . htmlspecialchars($joke['image'], ENT_QUOTES, 'UTF-8') ?>" 
                         alt="Joke image" 
                         class="joke-image"
                         onerror="this.style.display='none'">
                <?php endif; ?>
                
                <div class="joke-content">
                    <blockquote>
                        <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
                    </blockquote>
                    <small>Date: <?= htmlspecialchars(date('D d M Y', strtotime($joke['jokedate'])), ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No jokes found.</p>
    <?php endif; ?>
</body>
</html>