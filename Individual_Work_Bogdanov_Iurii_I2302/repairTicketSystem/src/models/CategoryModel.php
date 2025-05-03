<?php

/**
 * Файл: CategoryModel.php
 * Назначение: Модель взаимодействия с таблицей категорий в базе данных для проекта repairTicketSystem.
 *
 * @package RepairTicketSystem\Models
 */

require_once __DIR__ . '/../helpers/db.php';

/**
 * Класс CategoryModel
 *
 * Предоставляет методы для работы с таблицей `categories`.
 */
class CategoryModel
{
    /**
     * Получает список всех категорий из базы данных.
     *
     * Метод выполняет SQL-запрос на выборку всех категорий, отсортированных по имени.
     *
     * @return array Массив ассоциативных массивов категорий (id, name).
     */
    public static function getAll(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
