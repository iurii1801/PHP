<?php
/**
 * Базовый макет страницы.
 *
 * Подключает указанный шаблон контента ($template)
 * и отображает простую навигацию.
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Книга рецептов</title>
    <link rel="stylesheet" href="/recipe-book/public/styles.css">
</head>
<body>
    <nav>
    <a href="/recipe-book/public/index.php">Главная</a> |
    <a href="/recipe-book/public/recipe/create.php">Добавить рецепт</a>
</nav>
<hr>

    <?php include $template; ?>
</body>
</html>
