<?php

/**
 * Файл: dashboard.php
 * Назначение: Панель пользователя для просмотра и фильтрации его заявок.
 *
 * Отображает список заявок текущего пользователя.
 * Пользователь может отфильтровать заявки по статусу (ожидание, подтверждено, отклонено).
 *
 * @package RepairTicketSystem\Templates
 */

$templatePath = __FILE__;
require_once __DIR__ . '/../helpers/db.php';

$pdo = getPDO();
$userId = $_SESSION['user']['id'];
$statusFilter = $_GET['status'] ?? '';

/**
 * Получение заявок пользователя с фильтрацией по статусу.
 */
if ($statusFilter) {
    $stmt = $pdo->prepare("
        SELECT r.id, c.name AS category, d.name AS device, r.problem_description, r.urgency, r.status, t.slot_time
        FROM requests r
        JOIN categories c ON r.category_id = c.id
        JOIN devices d ON r.device_id = d.id
        JOIN time_slots t ON r.time_slot_id = t.id
        WHERE r.user_id = ? AND r.status = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$userId, $statusFilter]);
} else {
    $stmt = $pdo->prepare("
        SELECT r.id, c.name AS category, d.name AS device, r.problem_description, r.urgency, r.status, t.slot_time
        FROM requests r
        JOIN categories c ON r.category_id = c.id
        JOIN devices d ON r.device_id = d.id
        JOIN time_slots t ON r.time_slot_id = t.id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$userId]);
}

$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Мои заявки</h2>

<!-- Форма фильтрации по статусу -->
<form method="GET" action="index.php" style="margin-bottom: 20px;">
    <input type="hidden" name="page" value="dashboard">
    <label for="status">Фильтр по статусу:</label>
    <select name="status" id="status">
        <option value="">Все</option>
        <option value="ожидание" <?= ($statusFilter === 'ожидание') ? 'selected' : '' ?>>Ожидание</option>
        <option value="подтверждено" <?= ($statusFilter === 'подтверждено') ? 'selected' : '' ?>>Подтверждено</option>
        <option value="отклонено" <?= ($statusFilter === 'отклонено') ? 'selected' : '' ?>>Отклонено</option>
    </select>
    <button type="submit">Найти</button>
</form>

<!-- Список заявок -->
<?php if (count($requests) === 0): ?>
    <p>Заявки не найдены.</p>
<?php else: ?>
    <ul class="recipe-list">
        <?php foreach ($requests as $req): ?>
            <li>
                <strong>Услуга:</strong> <?= htmlspecialchars($req['category']) ?><br>
                <strong>Устройство:</strong> <?= htmlspecialchars($req['device']) ?><br>
                <strong>Описание:</strong> <?= htmlspecialchars($req['problem_description']) ?><br>
                <strong>Срочность:</strong> <?= htmlspecialchars($req['urgency']) ?><br>
                <strong>Слот:</strong> <?= date('d.m.Y H:i', strtotime($req['slot_time'])) ?><br>
                <strong>Статус:</strong>
                <?php if ($req['status'] === 'подтверждено'): ?>
                    <span style="color:green; font-weight:bold;">Подтверждено</span>
                <?php elseif ($req['status'] === 'отклонено'): ?>
                    <span style="color:red; font-weight:bold;">Отклонено</span>
                <?php else: ?>
                    <span style="color:gray;">Ожидает подтверждения</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
