<?php
/**
 * Обработчик редактирования рецепта.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../../src/db.php';

$pdo = getPDO();

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$category = (int)($_POST['category'] ?? 0);
$ingredients = trim($_POST['ingredients'] ?? '');
$description = trim($_POST['description'] ?? '');
$tags = trim($_POST['tags'] ?? '');
$steps = trim($_POST['steps'] ?? '');

if (!$id || $title === '' || $category === 0) {
    header("Location: /public/recipe/edit.php?id=$id&error=1");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE recipes
    SET title = :title,
        category = :category,
        ingredients = :ingredients,
        description = :description,
        tags = :tags,
        steps = :steps
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id,
    ':title' => $title,
    ':category' => $category,
    ':ingredients' => $ingredients,
    ':description' => $description,
    ':tags' => $tags,
    ':steps' => $steps,
]);

header("Location: /public/recipe/show.php?id=$id");
exit;

