<?php

/**
 * Файл: SlotModel.php
 * Назначение: Модель взаимодействия с таблицей временных слотов в базе данных проекта repairTicketSystem.
 *
 * @package RepairTicketSystem\Models
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Класс SlotModel
 *
 * Отвечает за получение доступных временных слотов из таблицы `time_slots`.
 */
class SlotModel
{
    /**
     * Получить все свободные временные слоты.
     *
     * Выполняет SQL-запрос на выборку всех слотов, которые ещё не заняты (is_booked = 0),
     * и возвращает их отсортированными по времени.
     *
     * @return array Массив ассоциативных массивов с данными о свободных слотах (id, slot_time и др.).
     */
    public static function getAvailable(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM time_slots WHERE is_booked = 0 ORDER BY slot_time ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
