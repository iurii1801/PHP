<?php

/**
 * Файл: password_reset_request.php
 * Назначение: Страница запроса кода восстановления пароля пользователя.
 * Отображает форму для ввода Email и отправки запроса на восстановление.
 *
 * @package RepairTicketSystem\Templates
 * @var string $templatePath Путь к текущему шаблону (для layout.php)
 */

$templatePath = __FILE__;
?>

<h2>Восстановление пароля</h2>

<form method="POST" action="/repairTicketSystem/public/index.php?page=password_reset_request" class="main-form">
    <label for="email">Введите ваш Email</label>
    <input type="email" name="email" id="email" required>

    <button type="submit" class="button">Получить код</button>
</form>
