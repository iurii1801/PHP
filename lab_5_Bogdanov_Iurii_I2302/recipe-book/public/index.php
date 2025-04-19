<?php
/**
 * Главная точка входа.
 * Загружает рецепты из базы данных с пагинацией и подключает шаблон отображения.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../src/db.php';

// Включение отображения ошибок (на этапе отладки)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = getPDO();

// Количество рецептов на странице
$perPage = 5;

/**
 * Номер текущей страницы, полученный из GET-запроса (?page=2 и т.д.)
 *
 * @var int $page
 */
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

/**
 * Сдвиг для SQL-запроса (OFFSET)
 *
 * @var int $offset
 */
$offset = ($page - 1) * $perPage;

/**
 * Получение общего количества рецептов
 */
$totalRecipes = $pdo->query("SELECT COUNT(*) FROM recipes")->fetchColumn();
$totalPages = ceil($totalRecipes / $perPage);

/**
 * Получение рецептов с пагинацией
 *
 * @var array $recipes
 */
$stmt = $pdo->prepare("
    SELECT r.*, c.name AS category_name
    FROM recipes r
    JOIN categories c ON r.category = c.id
    ORDER BY r.created_at DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$recipes = $stmt->fetchAll();

/**
 * Сделать переменные глобальными для шаблона layout
 */
$GLOBALS['page'] = $page;
$GLOBALS['totalPages'] = $totalPages;

/**
 * Подключение шаблона
 */
$template = __DIR__ . '/../templates/index.php';
include __DIR__ . '/../templates/layout.php';
