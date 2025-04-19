<?php
/**
 * Шаблон отображения одного рецепта.
 *
 * @var array $recipe
 */

require_once __DIR__ . '/../../src/db.php';
$pdo = getPDO();
$recipeId = $recipe['id'];

// Шаги приготовления
$steps = $pdo->prepare("
    SELECT step_number, description
    FROM steps
    WHERE recipe_id = :id
    ORDER BY step_number
");
$steps->execute([':id' => $recipeId]);
$stepList = $steps->fetchAll();

// Теги
$tags = $pdo->prepare("
    SELECT t.name
    FROM tags t
    JOIN recipe_tag rt ON t.id = rt.tag_id
    WHERE rt.recipe_id = :id
    ORDER BY t.name
");
$tags->execute([':id' => $recipeId]);
$tagList = $tags->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="recipe-container">
    <h1><?= htmlspecialchars($recipe['title']) ?></h1>

    <p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name']) ?></p>
    <p><strong>Дата добавления:</strong> <?= $recipe['created_at'] ?></p>

    <?php if ($recipe['description']): ?>
        <h3>Описание</h3>
        <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
    <?php endif; ?>

    <?php if ($recipe['ingredients']): ?>
        <h3>Ингредиенты</h3>
        <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
    <?php endif; ?>

    <?php if ($stepList): ?>
        <h3>Шаги приготовления</h3>
        <ol>
            <?php foreach ($stepList as $step): ?>
                <li><?= nl2br(htmlspecialchars($step['description'])) ?></li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php if ($tagList): ?>
        <h3>Теги</h3>
        <p><?= implode(', ', array_map('htmlspecialchars', $tagList)) ?></p>
    <?php endif; ?>

    <a href="../index.php" class="details-button">Назад к списку</a>

    <form method="POST" action="delete.php" class="form-inline" onsubmit="return confirm('Вы уверены, что хотите удалить рецепт?');">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
        <button type="submit" class="delete-button">Удалить рецепт</button>
    </form>
</div>
