<?php

/**
 * Файл: add_slot_handler.php
 * Назначение: Обработка добавления нового доступного слота для записи.
 *
 * Позволяет администратору добавить слот времени для пользовательских заявок.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Обрабатывает добавление нового временного слота.
 *
 * Получает значение поля 'slot_time' из POST-запроса, валидирует его,
 * и сохраняет в таблицу time_slots.
 *
 * @return void
 */
function handleAddSlot(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $slot = $_POST['slot_time'] ?? '';

    // Проверка наличия значения
    if (empty($slot)) {
        echo '<div class="error-box">Укажите дату и время</div>';
        return;
    }

    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO time_slots (slot_time) VALUES (?)");
        $stmt->execute([$slot]);

        echo '<div class="success-box">Слот добавлен</div>';
    } catch (PDOException $e) {
        echo '<div class="error-box">Ошибка при добавлении слота: ' . $e->getMessage() . '</div>';
    }
}
