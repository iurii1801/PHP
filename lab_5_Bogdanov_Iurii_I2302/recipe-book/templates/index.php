<?php
/**
 * Шаблон вывода всех рецептов с пагинацией.
 *
 * @var array $recipes
 * @global int $page
 * @global int $totalPages
 */
global $page, $totalPages;
?>

<h1>Все рецепты</h1>

<?php if (empty($recipes)): ?>
    <p>Рецепты не найдены.</p>
<?php else: ?>
    <ul class="recipe-list">
        <?php foreach ($recipes as $recipe): ?>
            <li>
                <strong><?= htmlspecialchars($recipe['title']) ?></strong><br>
                Категория: <?= htmlspecialchars($recipe['category_name']) ?><br>
                Добавлен: <?= $recipe['created_at'] ?><br><br>

                <div class="actions">
    <a href="recipe/show.php?id=<?= $recipe['id'] ?>" class="details-button">Подробнее</a>
    <form method="POST" action="recipe/delete.php" class="form-inline" onsubmit="return confirm('Удалить рецепт?');">
        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
        <button type="submit" class="delete-button">Удалить</button>
    </form>
</div>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <strong>[<?= $i ?>]</strong>
                <?php else: ?>
                    <a href="index.php?page=<?= $i ?>">[<?= $i ?>]</a>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
