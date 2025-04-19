<?php
/**
 * Шаблон формы добавления рецепта.
 * Загружает категории и теги из базы данных, отображает ошибки,
 * сохраняет старые значения из сессии.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';
session_start();

$pdo = getPDO();

// Категории
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Теги
$tagOptions = $pdo->query("SELECT * FROM tags ORDER BY name")->fetchAll();

// Данные из сессии
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];

$selectedTags = $old['tags'] ?? [];
$stepValues = $old['steps'] ?? ['']; // хотя бы один шаг

unset($_SESSION['old'], $_SESSION['errors']);
?>

<h1>Добавить рецепт</h1>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="save.php" class="main-form">
    <label for="title">Название:</label><br>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>"><br><br>

    <label for="category">Категория:</label><br>
    <select name="category" id="category">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= (isset($old['category']) && $old['category'] == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="ingredients">Ингредиенты:</label><br>
    <textarea name="ingredients" id="ingredients"><?= htmlspecialchars($old['ingredients'] ?? '') ?></textarea><br><br>

    <label for="description">Описание:</label><br>
    <textarea name="description" id="description"><?= htmlspecialchars($old['description'] ?? '') ?></textarea><br><br>

    <label for="tags">Теги:</label><br>
    <select name="tags[]" multiple size="5">
        <?php foreach ($tagOptions as $tag): ?>
            <option value="<?= $tag['id'] ?>" <?= in_array($tag['id'], $selectedTags) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Шаги приготовления:</label><br>
    <div id="steps-wrapper">
        <?php foreach ($stepValues as $step): ?>
            <textarea name="steps[]" rows="2"><?= htmlspecialchars($step) ?></textarea><br>
        <?php endforeach; ?>
    </div>
    <button type="button" onclick="addStep()" class="button">+ Добавить шаг</button><br><br>

    <button type="submit" class="button">Сохранить</button>
</form>

<script>
function addStep() {
    const wrapper = document.getElementById('steps-wrapper');
    const textarea = document.createElement('textarea');
    textarea.name = 'steps[]';
    textarea.rows = 2;
    wrapper.appendChild(textarea);
    wrapper.appendChild(document.createElement('br'));
}
</script>
