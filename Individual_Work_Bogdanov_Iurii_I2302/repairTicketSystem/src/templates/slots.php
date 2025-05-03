<?php

/**
 * Файл: slots.php
 * Назначение: Отображение всех временных слотов для администратора.
 * Позволяет просматривать список всех слотов и удалять свободные из них.
 *
 * Удаление происходит только в том случае, если слот не занят.
 * Данные извлекаются из базы данных и отображаются в табличном виде.
 *
 * @package RepairTicketSystem\Admin
 * @var string $templatePath Путь к шаблону, используемый layout.php
 */

$templatePath = __FILE__;
require_once __DIR__ . '/../helpers/db.php';

$pdo = getPDO();

// Обработка удаления слота, если он не занят
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $id = (int) $_GET['id'];

    // Проверка, свободен ли слот
    $stmt = $pdo->prepare("SELECT is_booked FROM time_slots WHERE id = ?");
    $stmt->execute([$id]);
    $isBooked = $stmt->fetchColumn();

    if (!$isBooked) {
        $pdo->prepare("DELETE FROM time_slots WHERE id = ?")->execute([$id]);
        header("Location: /repairTicketSystem/public/index.php?page=slots");
        exit;
    } else {
        echo '<div class="error-box">Нельзя удалить занятый слот.</div>';
    }
}

// Получение всех слотов из базы данных
$slots = $pdo->query("SELECT id, slot_time, is_booked FROM time_slots ORDER BY slot_time")->fetchAll();
?>

<h2>Все слоты</h2>

<table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дата и время</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($slots as $slot): ?>
            <tr>
                <td><?= $slot['id'] ?></td>
                <td><?= date('d.m.Y H:i', strtotime($slot['slot_time'])) ?></td>
                <td>
                    <?php if ($slot['is_booked']): ?>
                        <span style="color:red;">Занят</span>
                    <?php else: ?>
                        <span style="color:green;">Свободен</span> |
                        <a href="/repairTicketSystem/public/index.php?page=slots&action=delete&id=<?= $slot['id'] ?>"
                           onclick="return confirm('Удалить слот?')" title="Удалить слот">Удалить</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
