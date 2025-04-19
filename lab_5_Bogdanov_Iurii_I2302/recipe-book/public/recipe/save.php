<?php
/**
 * Обработчик добавления рецепта.
 * Сохраняет данные в таблицы: recipes, steps, recipe_tag.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';
require_once __DIR__ . '/../../src/helpers.php';
session_start();

/**
 * Очистка значения
 *
 * @param string|null $value
 * @return string
 */
function clean(?string $value): string {
    return trim(htmlspecialchars($value ?? ''));
}

// Получение данных
$title = clean($_POST['title'] ?? '');
$category = (int)($_POST['category'] ?? 0);
$ingredients = clean($_POST['ingredients'] ?? '');
$description = clean($_POST['description'] ?? '');
$tags = $_POST['tags'] ?? [];
$steps = $_POST['steps'] ?? [];

$_SESSION['old'] = $_POST;

$errors = [];

// Валидация
if ($title === '') {
    $errors[] = 'Название обязательно.';
}
if ($category === 0) {
    $errors[] = 'Категория обязательна.';
}
if (empty(array_filter($steps))) {
    $errors[] = 'Добавьте хотя бы один шаг.';
}
if ($ingredients === '') {
    $errors[] = 'Ингредиенты обязательны.';
}
if ($description === '') {
    $errors[] = 'Описание обязательно.';
}
if (count($tags) < 1) {
    $errors[] = 'Выберите хотя бы один тег.';
}  

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: create.php');
    exit;
}

try {
    $pdo = getPDO();
    $pdo->beginTransaction();

    // Вставка рецепта
    $stmt = $pdo->prepare("
        INSERT INTO recipes (title, category, ingredients, description)
        VALUES (:title, :category, :ingredients, :description)
    ");
    $stmt->execute([
        ':title' => $title,
        ':category' => $category,
        ':ingredients' => $ingredients,
        ':description' => $description
    ]);

    $recipeId = $pdo->lastInsertId();

    // Вставка шагов
    $stepStmt = $pdo->prepare("
        INSERT INTO steps (recipe_id, step_number, description)
        VALUES (:recipe_id, :step_number, :description)
    ");

    $stepNumber = 1;
    foreach ($steps as $step) {
        $step = trim($step);
        if ($step !== '') {
            $stepStmt->execute([
                ':recipe_id' => $recipeId,
                ':step_number' => $stepNumber++,
                ':description' => $step
            ]);
        }
    }

    // Вставка тегов
    $tagStmt = $pdo->prepare("
        INSERT INTO recipe_tag (recipe_id, tag_id)
        VALUES (:recipe_id, :tag_id)
    ");

    foreach ($tags as $tagId) {
        $tagStmt->execute([
            ':recipe_id' => $recipeId,
            ':tag_id' => (int)$tagId
        ]);
    }

    $pdo->commit();
    unset($_SESSION['old']);
    header("Location: show.php?id=$recipeId");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['errors'] = ['Ошибка при сохранении: ' . $e->getMessage()];
    header('Location: create.php');
    exit;
}
