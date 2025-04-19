<?php
/**
 * Страница редактирования рецепта.
 * Загружает рецепт и категории, затем подключает шаблон формы.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';
session_start();

$pdo = getPDO();

/** @var int|null $id */
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    header('Location: /public/index.php');
    exit;
}

// Получение рецепта
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
$stmt->execute([':id' => $id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    http_response_code(404);
    exit('Рецепт не найден');
}

// Категории
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$template = __DIR__ . '/../../templates/recipe/edit.php';
include __DIR__ . '/../../templates/layout.php';
