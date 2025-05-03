<?php

/**
 * Файл: view.php
 * Назначение: Отвечает за отображение шаблонов страниц в приложении repairTicketSystem.
 * Используется для подключения представлений с общей разметкой layout.
 *
 * @package RepairTicketSystem\Helpers
 */

/**
 * Подключает шаблон страницы через layout.
 *
 * Шаблон ищется по имени, переданному в параметре, и подставляется в layout.php.
 * Если файл шаблона не найден, выводится сообщение об ошибке.
 *
 * @param string $template Имя шаблона (без расширения .php), расположенного в папке templates.
 *
 * @return void
 */
function render(string $template): void
{
    $templatePath = __DIR__ . '/../templates/' . $template . '.php';

    if (file_exists($templatePath)) {
        require_once __DIR__ . '/../templates/layout.php';
    } else {
        echo "<h2>Шаблон не найден: {$template}</h2>";
    }
}
