<?php

/**
 * Файл: db.php
 * Назначение: Устанавливает и возвращает PDO-соединение с базой данных.
 * Используется во всём проекте repairTicketSystem для подключения к MySQL.
 *
 * @package RepairTicketSystem\Helpers
 */

declare(strict_types=1);

/**
 * Получает singleton PDO-соединение к базе данных.
 *
 * При первом вызове устанавливает новое соединение и сохраняет его
 * в статической переменной. При последующих вызовах возвращает уже существующее соединение.
 *
 * @return PDO Объект подключения к базе данных с настроенной кодировкой и режимом ошибок.
 */
function getPDO(): PDO
{
    static $pdo;

    if ($pdo === null) {
        $host = 'localhost';
        $dbname = 'repair_system';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Завершает выполнение скрипта с сообщением об ошибке подключения
            die("Ошибка подключения к БД: " . $e->getMessage());
        }
    }

    return $pdo;
}
