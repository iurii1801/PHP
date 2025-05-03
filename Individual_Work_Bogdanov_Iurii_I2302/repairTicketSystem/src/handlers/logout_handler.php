<?php

/**
 * Файл: logout_handler.php
 * Назначение: Обработка выхода пользователя из системы.
 *
 * Завершает текущую сессию пользователя и перенаправляет его на главную страницу.
 *
 * @package RepairTicketSystem\Handlers
 */

/**
 * Завершает сессию пользователя и очищает все сессионные данные.
 *
 * @return void
 */
function handleLogout(): void
{
    // Очистка всех переменных сессии
    session_unset();

    // Уничтожение сессии
    session_destroy();

    // Перенаправление на главную страницу
    header("Location: /repairTicketSystem/public/index.php?page=home");
    exit;
}
