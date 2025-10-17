<?php foreach ($jokes as $joke): ?>
   <blockquote>
    <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
    <?php if (!empty($joke['image'])): ?>
        <div class="joke-image">
            <a href="<?= htmlspecialchars($joke['image'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                <img src="<?= htmlspecialchars($joke['image'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="Joke image" style="max-width:200px;display:block;margin-top:0.5em;">
            </a>
        </div>
    <?php endif; ?>
    <form action="deletejoke.php" method="post">
        <input type="hidden" name="id" value="<?= $joke['id'] ?>">
        <input type="submit" value="Delete">
    </form>
    </blockquote>

<?php endforeach; ?>