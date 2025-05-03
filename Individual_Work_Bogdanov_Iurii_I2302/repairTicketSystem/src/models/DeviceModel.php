<?php

/**
 * Файл: DeviceModel.php
 * Назначение: Модель взаимодействия с таблицей устройств в базе данных проекта repairTicketSystem.
 *
 * @package RepairTicketSystem\Models
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Класс DeviceModel
 *
 * Отвечает за получение данных о типах устройств из таблицы `devices`.
 */
class DeviceModel
{
    /**
     * Получает все доступные типы устройств.
     *
     * Выполняет SQL-запрос на выборку всех записей из таблицы `devices`, отсортированных по имени.
     *
     * @return array Массив ассоциативных массивов устройств (id, name).
     */
    public static function getAll(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM devices ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
