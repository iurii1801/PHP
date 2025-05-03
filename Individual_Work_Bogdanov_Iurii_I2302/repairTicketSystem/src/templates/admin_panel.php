<?php

/**
 * Файл: admin_panel.php
 * Назначение: Панель администратора для управления заявками пользователей.
 *
 * Этот шаблон предоставляет возможность:
 * - Просматривать все заявки пользователей;
 * - Подтверждать, отклонять и удалять заявки;
 * - Автоматически освобождать временные слоты при удалении заявок.
 *
 * @package RepairTicketSystem\Templates
 */

$templatePath = __FILE__;
require_once __DIR__ . '/../helpers/db.php';

$pdo = getPDO();

/**
 * Обработка GET-запросов администратора: подтверждение, отклонение и удаление заявок.
 */
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $pdo->prepare("UPDATE requests SET status = 'подтверждено' WHERE id = ?")->execute([$id]);
    } elseif ($action === 'reject') {
        $pdo->prepare("UPDATE requests SET status = 'отклонено' WHERE id = ?")->execute([$id]);
    } elseif ($action === 'delete') {
        // Получение ID слота перед удалением заявки
        $stmt = $pdo->prepare("SELECT time_slot_id FROM requests WHERE id = ?");
        $stmt->execute([$id]);
        $slotId = $stmt->fetchColumn();

        // Освобождение слота
        if ($slotId) {
            $pdo->prepare("UPDATE time_slots SET is_booked = 0 WHERE id = ?")->execute([$slotId]);
        }

        // Удаление заявки
        $pdo->prepare("DELETE FROM requests WHERE id = ?")->execute([$id]);
    }

    // Перенаправление для предотвращения повторной отправки запроса
    header("Location: /repairTicketSystem/public/index.php?page=admin_panel");
    exit;
}

// Получение всех заявок с детальной информацией
$stmt = $pdo->query("
    SELECT r.id, u.username, c.name AS category, d.name AS device, r.problem_description, r.urgency, r.status, t.slot_time
    FROM requests r
    JOIN users u ON r.user_id = u.id
    JOIN categories c ON r.category_id = c.id
    JOIN devices d ON r.device_id = d.id
    JOIN time_slots t ON r.time_slot_id = t.id
    ORDER BY r.created_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Заявки пользователей</h2>

<table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Услуга</th>
            <th>Устройство</th>
            <th>Описание</th>
            <th>Срочность</th>
            <th>Слот</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $req): ?>
        <tr>
            <td><?= $req['id'] ?></td>
            <td><?= htmlspecialchars($req['username']) ?></td>
            <td><?= htmlspecialchars($req['category']) ?></td>
            <td><?= htmlspecialchars($req['device']) ?></td>
            <td><?= htmlspecialchars($req['problem_description']) ?></td>
            <td><?= htmlspecialchars($req['urgency']) ?></td>
            <td><?= date('d.m.Y H:i', strtotime($req['slot_time'])) ?></td>
            <td>
                <?php if ($req['status'] === 'подтверждено'): ?>
                    <span style="color: green; font-weight: bold;">Подтверждено</span>
                <?php elseif ($req['status'] === 'отклонено'): ?>
                    <span style="color: red; font-weight: bold;">Отклонено</span>
                <?php else: ?>
                    <span style="color: gray;">Ожидает</span>
                <?php endif; ?>
            </td>
            <td class="admin-actions">
                <?php if ($req['status'] === 'ожидание'): ?>
                    <a href="?page=admin_panel&action=approve&id=<?= $req['id'] ?>">Подтвердить</a>
                    <a href="?page=admin_panel&action=reject&id=<?= $req['id'] ?>">Отклонить</a>
                    <a href="?page=admin_panel&action=delete&id=<?= $req['id'] ?>" onclick="return confirm('Удалить заявку?')">Удалить</a>
                <?php elseif ($req['status'] === 'подтверждено'): ?>
                    <span style="color: green;">Уже подтверждено</span>
                <?php elseif ($req['status'] === 'отклонено'): ?>
                    <span style="color: red;">Уже отклонено</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
