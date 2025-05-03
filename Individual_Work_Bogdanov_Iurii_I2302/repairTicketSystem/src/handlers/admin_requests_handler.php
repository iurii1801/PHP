<?php

/**
 * Файл: admin_requests_handler.php
 * Назначение: Обработчик получения всех заявок пользователей для отображения в админ-панели.
 *
 * Получает данные о заявках, включая имя пользователя, название услуги, модель устройства и слот времени.
 *
 * @package RepairTicketSystem\Handlers
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Получает список всех заявок с подробной информацией.
 *
 * Выполняется объединение таблиц заявок, пользователей, категорий, устройств и временных слотов.
 *
 * @param PDO $pdo Подключение к базе данных.
 * @return array Массив заявок с расширенными данными.
 */
function getAllRequests(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT r.*, 
               u.username, 
               c.name AS category_name, 
               d.name AS device_name, 
               s.time AS slot_time
        FROM requests r
        JOIN users u ON r.user_id = u.id
        JOIN categories c ON r.category_id = c.id
        JOIN devices d ON r.device_id = d.id
        JOIN time_slots s ON r.time_slot_id = s.id
        ORDER BY r.id DESC
    ");

    return $stmt->fetchAll();
}
