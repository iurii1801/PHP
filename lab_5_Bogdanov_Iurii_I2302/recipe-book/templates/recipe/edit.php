<?php
/**
 * Шаблон формы редактирования рецепта.
 *
 * @var array $recipe Текущий рецепт
 * @var array $categories Категории
 */
?>

<h1>Редактировать рецепт</h1>

<form method="POST" action="/src/handlers/recipe/edit.php" class="main-form">
    <input type="hidden" name="id" value="<?= $recipe['id'] ?>">

    <label>Название:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($recipe['title']) ?>"><br><br>

    <label>Категория:</label><br>
    <select name="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $recipe['category'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Ингредиенты:</label><br>
    <textarea name="ingredients"><?= htmlspecialchars($recipe['ingredients']) ?></textarea><br><br>

    <label>Описание:</label><br>
    <textarea name="description"><?= htmlspecialchars($recipe['description']) ?></textarea><br><br>

    <label>Теги:</label><br>
    <input type="text" name="tags" value="<?= htmlspecialchars($recipe['tags']) ?>"><br><br>

    <label>Шаги приготовления:</label><br>
    <textarea name="steps" rows="4"><?= htmlspecialchars($recipe['steps']) ?></textarea><br><br>

    <button type="submit">Сохранить изменения</button>
</form>
