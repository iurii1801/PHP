<?php

/**
 * Файл: add_slot.php
 * Назначение: Шаблон страницы для добавления нового доступного временного слота (для администратора).
 * Этот шаблон отображает HTML-форму, через которую администратор может добавить новую дату и время,
 * доступные для записи пользователей.
 *
 * @package RepairTicketSystem\Templates
 */

$templatePath = __FILE__;
?>

<h2>Добавление доступного слота</h2>

<form method="POST" class="main-form">
    <label for="slot_time">Дата и время</label>
    <input type="datetime-local" name="slot_time" id="slot_time" required>
    
    <button type="submit" class="button">Добавить</button>
</form>
