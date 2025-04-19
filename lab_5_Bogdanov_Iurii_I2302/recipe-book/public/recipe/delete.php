<?php
/**
 * Обработчик удаления рецепта по ID.
 * Удаляет рецепт, шаги и связи с тегами.
 *
 * @package RecipeBook
 */

require_once __DIR__ . '/../../src/db.php';

$pdo = getPDO();
$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: ../index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    echo "Ошибка при удалении: " . $e->getMessage();
}
