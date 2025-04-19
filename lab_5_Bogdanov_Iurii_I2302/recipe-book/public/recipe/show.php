<?php
/**
 * Страница просмотра одного рецепта.
 * Получает ID рецепта из параметра запроса и загружает его из базы.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';

$pdo = getPDO();

/**
 * Получение ID рецепта из параметра запроса (?id=...)
 *
 * @var int|null $id
 */
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Если ID не передан — перенаправление на главную
if (!$id) {
    header('Location: /public/index.php');
    exit;
}

/**
 * Получение рецепта по ID
 *
 * @var array|false $recipe
 */
$stmt = $pdo->prepare("
    SELECT r.*, c.name AS category_name
    FROM recipes r
    JOIN categories c ON r.category = c.id
    WHERE r.id = :id
");

$stmt->execute([':id' => $id]);
$recipe = $stmt->fetch();

// Если рецепт не найден — 404
if (!$recipe) {
    http_response_code(404);
    exit('Рецепт не найден');
}

// Подключение шаблона
$template = __DIR__ . '/../../templates/recipe/show.php';
include __DIR__ . '/../../templates/layout.php';
