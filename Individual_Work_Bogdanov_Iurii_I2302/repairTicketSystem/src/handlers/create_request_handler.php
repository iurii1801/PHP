<?php

/**
 * Файл: create_request_handler.php
 * Назначение: Обработка формы создания новой заявки на ремонт.
 *
 * Получает данные от пользователя, проверяет корректность ввода,
 * сохраняет заявку в базе данных и помечает выбранный слот как занятый.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Обрабатывает отправку формы заявки.
 *
 * Выполняет:
 * - Получение данных из формы;
 * - Валидацию обязательных полей;
 * - Добавление записи в таблицу requests;
 * - Обновление статуса слота.
 *
 * @return void
 */
function handleCreateRequest(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    // Получение данных от пользователя
    $userId = $_SESSION['user']['id'];
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $deviceId = (int) ($_POST['device_id'] ?? 0);
    $description = trim($_POST['problem_description'] ?? '');
    $urgency = $_POST['urgency'] ?? '';
    $slotId = (int) ($_POST['time_slot_id'] ?? 0);

    // Проверка обязательных полей
    if (!$categoryId || !$deviceId || !$description || !$urgency || !$slotId) {
        echo '<div class="error-box">Пожалуйста, заполните все поля формы.</div>';
        return;
    }

    try {
        $pdo = getPDO();

        // Сохраняем заявку в базу данных
        $stmt = $pdo->prepare("
            INSERT INTO requests (user_id, category_id, device_id, problem_description, urgency, time_slot_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $categoryId, $deviceId, $description, $urgency, $slotId]);

        // Обновляем слот — помечаем как занятый
        $pdo->prepare("UPDATE time_slots SET is_booked = 1 WHERE id = ?")->execute([$slotId]);

        echo '<div class="success-box">Заявка успешно отправлена!</div>';
    } catch (PDOException $e) {
        echo '<div class="error-box">Ошибка базы данных: ' . $e->getMessage() . '</div>';
    }
}
