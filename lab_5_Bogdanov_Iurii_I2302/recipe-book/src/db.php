<?php
/**
 * Получить PDO-соединение
 *
 * @return PDO
 */
function getPDO(): PDO {
    $config = require __DIR__ . '/../config/db.php';

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

    return new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
